<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Comments;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class CommentsController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Comments';
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        //$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        //unset($actions['view']); //use my own below

        return $actions;
    }


    //compare maxId on depend table
    /*public function actionDepend()
    {
        $row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='category'")->queryOne();
        return $row['last_update'];
    }*/
}
