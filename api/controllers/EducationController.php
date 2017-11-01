<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Education;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class EducationController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Education';
    
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create'], $actions['update']);

        return $actions;
    }



    public function prepareDataProvider()
    {
        // prepare and return a data provider for the "index" action
        $request=\Yii::$app->request->get();
        $text='';
        if(isset($request['text'])){
            $text=$request['text'];
        }
        $query =Education::find();

        $query->andFilterWhere(['or',['like','title',$text],['like','text',$text]]);


        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
            'sort'=> ['defaultOrder' => ['date'=>SORT_DESC]]
        ]);
    }
}
