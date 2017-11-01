<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use frontend\models\Page;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class PageController extends \yii\rest\ActiveController
{
    public $modelClass = 'frontend\models\Page';
    
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create'], $actions['update'],$actions['view']);

        return $actions;
    }

    public function prepareDataProvider()
    {
        if($lang=Yii::$app->request->get('lang')){
            Yii::$app->language=$lang;
        }
        $query =Page::find()->all();
        //$query['suka']="pidar";

        return $query;
    }

    public function actionView($id)
    {
        if($lang=Yii::$app->request->get('lang')){
            Yii::$app->language=$lang;
        }
        $model = Page::find()->where(['id'=>$id])->one();
        return $model;
    }
}
