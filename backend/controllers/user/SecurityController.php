<?php
namespace backend\controllers\user;

use dektrium\user\controllers\SecurityController as BaseSecurityController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use dektrium\user\filters\AccessRule;

class SecurityController extends BaseSecurityController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login','logout'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
}


?>