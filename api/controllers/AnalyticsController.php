<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Analytics;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class AnalyticsController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Analytics';
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        //unset($actions['view']); //use my own below

        return $actions;
    }

    public function prepareDataProvider()
    {
        // prepare and return a data provider for the "index" action
        $request=\Yii::$app->request->get();
        $ctg='';$text='';
        if(isset($request['text'])){
            $text=$request['text'];
        }
        $query =Analytics::find()->where(['status'=>1]);
        
        $query->andFilterWhere(['or',['like','title',$text],['like','text',$text]]);


        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
            'sort'=> ['defaultOrder' => ['date'=>SORT_DESC]]
        ]);
    }

    //compare maxId on depend table
    /*public function actionDepend()
    {
        $row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='news'")->queryOne();
        return (int)$row['last_update'];
    }*/
}
