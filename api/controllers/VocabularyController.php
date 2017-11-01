<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Vocabulary;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class VocabularyController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Vocabulary';
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create'], $actions['update']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        //unset($actions['view']); //use my own below

        return $actions;
    }

    public function prepareDataProvider()
    {
        $query =Vocabulary::find()->orderBy(['ordered_id'=>"ASC"])->asArray()->all();

        return $query;
    }

    //compare maxId on depend table
    public function actionDepend()
    {
        $row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='vocabulary'")->queryOne();
        return (int)$row['last_update'];
    }
}
