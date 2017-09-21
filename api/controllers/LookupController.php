<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use frontend\models\Lookup;

class LookupController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Lookup';
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        return $actions;
    }

    //compare maxId on depend table
    public function actionDepend()
    {
        //$row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='lookup'")->queryOne();
        //return $row['last_update'];

        $dir=Yii::getAlias('@frontend').'/web/images/report/';
        $num=rand(23,300);
        $tosave=$dir.$num;
        if (!file_exists($tosave)) {
            mkdir($tosave);
        }
        return 1;
    }
}
