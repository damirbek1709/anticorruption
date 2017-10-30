<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use dektrium\user\models\User;
use yii\filters\AccessControl;
use dektrium\user\helpers\Password;
use dektrium\user\models\RecoveryForm;

class AccountController extends \yii\rest\ActiveController
{
    public $modelClass = 'dektrium\user\models\User';

    public function actions()
    {
        $actions = parent::actions();
        //$actions['create']['scenario'] = 'register';
        $actions['update']['scenario'] = 'update';
        unset($actions['delete'], $actions['create'], $actions['index']);
        unset($actions['view'],$actions['update']); //use my own below

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'only' => ['update'],
        ];
        /*$behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'actions' => [
                        'options','login'
                    ],
                ],
                [
                    'allow' => true,
                    'roles' => [
                        'admin',
                    ],
                ],
            ],
        ];*/
        return $behaviors;
    }

    public function actionLogin()
    {
        $post = Yii::$app->request->post();
        $model = User::findOne(["username" => $post['login']]);
        if (empty($model)) {
            $model = User::findOne(["email" => $post['login']]);
            if (empty($model)) {
                return 'not_username';
            }
        }
        $pass=$post['password'];

        if (Password::validate($pass,$model->password_hash)) {
            $model->last_login_at = Yii::$app->formatter->asTimestamp(date_create());
            //$model->updateCounters(['logins' => 1]);
            $model->save(false);
            return $model; //return whole user model including auth_key or you can just return $model["auth_key"];
        } else {
            return "not_password";
        }
    }

    public function actionCreate()
    {
        $model=new User();
        $post=Yii::$app->request->post();
        $model->email=$post['email'];
        $model->username=$post['username'];
        $model->password=$post['password'];
        $model->scenario="register";
        if($model->register()){
            return ['message'=>'confirm'];
        }
        else {return $model->getErrors();}
    }

    public function actionForgot()
    {
        /** @var RecoveryForm $model */
        $model = \Yii::createObject([
            'class'    => RecoveryForm::className(),
            'scenario' => RecoveryForm::SCENARIO_REQUEST,
        ]);
        $model->email=Yii::$app->request->post('email');
        if ($model->email && User::findOne(["email" => $model->email])) {
            $model->sendRecoveryMessage();
            return ['message'=>'Вам отправлено письмо с инструкциями по смене пароля.'];
        }
        else {return ['message'=>'Пользователь с такой почтой не найден.'];}
    }

    public function actionView($id)
    {
        $user=[];
        $dao=Yii::$app->db;
        $row=$dao->createCommand("SELECT * FROM `user` WHERE id='{$id}'")->queryOne();
        if($row){
            $user['id']=$row['id'];
            $user['username']=$row['username'];
            $auth_token=Yii::$app->request->get('auth_key');
            if($auth_token && $auth_token==$row['auth_key']){
                $where="user_id='{$id}'";
            }
            else{
                $where="user_id='{$id}' AND `status`=1";
            }

            $user['reports']= count($dao->createCommand("SELECT id FROM report WHERE {$where}")->queryAll());
            $user['comments']= count($dao->createCommand("SELECT id FROM comments WHERE {$where}")->queryAll());
        }
        return $user;
    }

    public function actionSocial()
    {
        $post=Yii::$app->request->post();
        if(isset($post['provider']) && isset($post['puid'])){
            $provider=$post['provider']; //facebook, twitter, google, odnoklassniki
            $provider_user_id=$post['puid'];
        }
        else{
            return "error";
        }
        $data=Yii::$app->request->post('data');
        $email=Yii::$app->request->post('email');
        $social_username=Yii::$app->request->post('username');
        $name=Yii::$app->request->post('name');
        $result=null;

        if($provider && $provider_user_id){
            $dao=Yii::$app->db;
            $user_id=null;
            $has_social=false;

            //get user_id if they previously had used social login and got registered
            $social_row=$dao->createCommand("SELECT * FROM social_account WHERE provider='{$provider}' && client_id='{$provider_user_id}'")->queryOne();
            if($social_row){
                $has_social=true; //already has social account, so no need to create one
                if($social_row['user_id']){$user_id=$social_row['user_id'];}
            }
            if($user_id) //if they have registered then return user data
            {
                $result=$this->getUser($user_id);
            }
            else //if didn't then register and login
            {
                if($email) //if email is provided then check if user with this email has already registered
                {
                    $user_exists=$dao->createCommand("SELECT id FROM `user` WHERE email='{$email}'")->queryOne();
                    if(!empty($user_exists)) //if did then return user
                    {
                        $user_id=$user_exists['id'];
                        $result=$this->getUser($user_id);
                    }
                    else //create user
                    {
                        $model=new User();
                        $model->email=$email;
                        $model->username=$this->generateUsername($model,$social_username,$name,$email);
                        $model->create();
                        $result=$model;
                        $user_id=$model->id;
                    }

                    if($has_social) //if social_account was created before but didn't have email or user_id
                    {
                        if(!empty($social_row['email'])){$upd_email=$social_row['email'];} else{$upd_email=null;}
                        if($user_id){$upd_user_id=$user_id;} else{$upd_user_id=null;}
                        if($upd_email || $upd_user_id){
                            $dao->createCommand("UPDATE social_account SET email='{$upd_email}', user_id='{$upd_user_id}' WHERE id='{$social_row['id']}'")->execute();
                        }
                    }
                }
                else //if email is not provided then ask email before registering new user
                {
                    $result="ask_email";
                }
                if(!$has_social){
                    $dao->createCommand()->insert('social_account', [
                        'provider' => $provider,
                        'client_id' => $provider_user_id,
                        'user_id'=>$user_id,
                        'data'=>$data,
                        'email'=>$email,
                        'username'=>$social_username
                    ])->execute();
                }
            }
        }

        return $result;
    }

    protected function cyrToLat($text){
        $cyr = [
            'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
        ];
        $lat = [
            'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','E','Yu','Ya'
        ];
        return str_replace($cyr, $lat, $text);
        
    }
    
    protected function generateUsername(User $model,$social_username,$name, $email){
        $any_name="user";
        if($social_username){
            $any_name=$social_username;
            if($model->validate($social_username)){return $social_username;}
        }
        if($name){
            $name=$this->cyrToLat($name);
            $neymar=explode(" ",$name);
            $any_name=$neymar[0];
            if($model->validate($neymar[0])){ return $neymar[0];}
            if(isset($neymar[1])){if($model->validate($neymar[1])){ return $neymar[1];}}
            if(isset($neymar[1])){
                $name=str_replace(" ","",$name);
                if($model->validate($name)){ return $name;}}
        }
        if($email){
            $ename=explode("@",$email);
            if(!empty($ename[0])){
                $any_name=$ename[0];
                if($model->validate($ename[0])){
                    return $ename[0];
                }
            }
        }

        $rand=rand(1,1000);
        return $any_name.$rand;

    }

    protected function getUser($user_id){
        $model = User::findOne(["id" => $user_id]);
        $model->last_login_at = Yii::$app->formatter->asTimestamp(date_create());
        //$model->updateCounters(['logins' => 1]);
        $model->save(false);
        return $model;
    }

    public function actionUpdate($id)
    {
        $user_id=Yii::$app->user->id;
        $username=Yii::$app->request->post("username");
        $result=["username"=>["Ошибка"]];
        if($username && $user_id){
            $model = User::findOne(["id" => $user_id]);
            $model->username = $username;
            if($model->save()){$result=["success"=>[true]];}
            else{$result=$model->errors;}
        }
        return $result;
    }
}
