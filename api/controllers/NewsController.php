<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\News;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class NewsController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\News';
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['view']); //use my own below

        return $actions;
    }

    public function prepareDataProvider()
    {
        $query =News::find();

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
            'sort'=> ['defaultOrder' => ['date'=>SORT_DESC]]
        ]);
    }

    public function actionView($id)
    {
        $model = News::find()->where(['id'=>$id])->with('category', 'comments')->asArray()->one();
        /*unset($model['form_id'],$model['location_id']);*/
        return $model;
    }

    //compare maxId on depend table
    /*public function actionDepend()
    {
        $row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='category'")->queryOne();
        return $row['last_update'];
    }*/
}
