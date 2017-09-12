<?php
namespace app\controllers\user;

use dektrium\user\controllers\AdminController as BaseAdminController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use dektrium\user\filters\AccessRule;

class AdminController extends BaseAdminController
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
                        'actions' => ['switch','update-profile'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','delete','update','block','create'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }
}


?>