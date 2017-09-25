<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Report;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class ReportController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Report';
    
    public function actions()
    {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        unset($actions['view']); //use my own below

        return $actions;
    }


    public function prepareDataProvider()
    {
        // prepare and return a data provider for the "index" action
        $request=\Yii::$app->request->get();
        $ctg='';$verified='';$text='';$user_id="";
        if(isset($request['category_id'])){
            $ctg=$request['category_id'];
        }
        if(isset($request['verified'])){
            $verified=$request['verified'];
        }
        if(isset($request['text'])){
            $text=$request['text'];
        }
        if(isset($request['user_id'])){
            $user_id=$request['user_id'];
        }
        $query =Report::find();


        //$query->filterWhere(['incident_verified'=>$verified]);
        $query->andFilterWhere(['or',['like','title',$text],['like','text',$text]]);
        $query->andFilterWhere(['user_id'=> $user_id]);

        /*if($ctg){
            $query->joinWith([
                'incidentCategories' => function ($query) use($ctg) {
                    $query->andFilterWhere(['in','category_id', $ctg]);
                }]);

        }*/

        //$query->orderBy(['omapincident.id' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);
    }

    public function actionView($id)
    {
        $model = Report::find()->where(['id'=>$id])->with('authority', 'department', 'city', 'comments','type')->asArray()->one();
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
