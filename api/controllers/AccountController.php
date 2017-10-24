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

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['create', 'login', 'forgot', 'social'],
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
        $username=$social_username;
        $result=null;

        if($provider && $provider_user_id){
            $dao=Yii::$app->db;
            $user_id=false;
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
                        if(!$social_username){$username=$model->generateUsername();}
                        $model->username=$username;
                        $model->create();
                        $result=$model;
                        $user_id=$model->id;
                    }

                    if($has_social && empty($social_row['email'])) //if social_account was created before but didn't have email
                    {
                        $dao->createCommand("UPDATE social_account SET email='{$email}' WHERE id='{$social_row['id']}'")->execute();
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

    protected function getUser($user_id){
        $model = User::findOne(["id" => $user_id]);
        $model->last_login_at = Yii::$app->formatter->asTimestamp(date_create());
        //$model->updateCounters(['logins' => 1]);
        $model->save(false);
        return $model;
    }
}
