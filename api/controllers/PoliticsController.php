<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Politics;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class PoliticsController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Politics';
    
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create'], $actions['update'], $actions['view']);

        return $actions;
    }



    public function prepareDataProvider()
    {
        if($lang=Yii::$app->request->get('lang')){
            Yii::$app->language=$lang;
        }
        // prepare and return a data provider for the "index" action
        $request=\Yii::$app->request->get();
        $text='';
        if(isset($request['text'])){
            $text=$request['text'];
        }
        $query =Politics::find();

        $query->andFilterWhere(['or',['like','title',$text],['like','text',$text]]);


        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort'=> ['defaultOrder' => ['date'=>SORT_DESC]]
        ]);
    }

    public function actionView($id)
    {
        if($lang=Yii::$app->request->get('lang')){
            Yii::$app->language=$lang;
        }
        return Politics::find()->where(['id'=>$id])->one();
    }
}
