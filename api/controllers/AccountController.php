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
        unset($actions['delete'], $actions['create']);

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['create', 'login', 'forgot'],
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
}
