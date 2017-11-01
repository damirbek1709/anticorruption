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
        unset($actions['delete'], $actions['create'], $actions['update']);

        return $actions;
    }

    public function prepareDataProvider()
    {
        $query =Page::find()->where(['app'=>1])->asArray()->all();

        return $query;
    }
}
