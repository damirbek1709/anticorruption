<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use common\models\User;
use yii\filters\AccessControl;

class UserController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\User';

    public function actions()
    {
        $actions = parent::actions();
        $actions['create']['scenario'] = 'create';
        $actions['update']['scenario'] = 'update';

        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' => ['create', 'login', 'resetpassword'],
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
        $model = User::findOne(["email" => $post["email"]]);
        if (empty($model)) {
            return 'not_username';
        }
        if ($model->validatePassword($post["password"])) {
            //$model->last_login = Yii::$app->formatter->asTimestamp(date_create());
            //$model->updateCounters(['logins' => 1]);
            //$model->save(false);
            return $model; //return whole user model including auth_key or you can just return $model["auth_key"];
        } else {
            return "not_password";
        }
    }
}
