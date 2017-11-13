<?php

namespace backend\controllers;
use frontend\models\PoliticsSearch;
use frontend\models\Politics;
use yii\filters\AccessControl;
use dektrium\user\filters\AccessRule;
use yii\data\ActiveDataProvider;
use frontend\models\Vocabulary;
class PoliticsController extends \yii\web\Controller
{


    public function actionCategory($id)
    {
        $searchModel = new PoliticsSearch();

        $dataProvider = new ActiveDataProvider([
            'query' => PoliticsSearch::find()->where(['category_id' => $id]),
            'sort' => ['defaultOrder' => ['date' => SORT_DESC]],
        ]);
        $title = Vocabulary::find()->where(['id'=>$id,'key'=>'politics_category'])->select('value')->scalar();

        return $this->render('@frontend/views/politics/list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'title'=>$title,
        ]);
    }

}
