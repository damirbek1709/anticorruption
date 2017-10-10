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
        // prepare and return a data provider for the "index" action
        $request=\Yii::$app->request->get();
        $ctg='';$text='';
        if(isset($request['category_id'])){
            $ctg=$request['category_id'];
        }
        if(isset($request['text'])){
            $text=$request['text'];
        }
        $query =News::find();


        $query->filterWhere(['category_id'=>$ctg]);
        $query->andFilterWhere(['or',['like','title',$text],['like','text',$text]]);


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
    public function actionDepend()
    {
        $row=Yii::$app->db->createCommand("SELECT * FROM depend WHERE `table_name`='news'")->queryOne();
        return (int)$row['last_update'];
    }
}
