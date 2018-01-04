<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CommentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Комментарии');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'date',
            'email:email',
            [
                'attribute' => 'text',
                'contentOptions' => ['style' => 'max-width:300px; white-space: normal;'],
                'value' => function ($model) {
                    return $model->text;
                }
            ],
            [
                'format' => 'raw',
                'attribute' => 'material',
                'contentOptions' => ['style' => 'width:200px; white-space: normal;'],
                'value' => function ($model) {
                    if ($model->report_id && $model->report) {
                        return Html::a($model->report->title, Yii::$app->urlManagerFrontend->createAbsoluteUrl(['report/view', 'id' => $model->report_id]), ['target' => '_blank']);
                    } elseif ($model->news_id && $model->report) {
                        return Html::a($model->news->title, Yii::$app->urlManagerFrontend->createAbsoluteUrl(['news/view', 'id' => $model->news_id]), ['target' => '_blank']);
                    } elseif ($model->news_id && $model->news) {
                        return Html::a($model->authority->title, Yii::$app->urlManagerFrontend->createAbsoluteUrl(['authority/view', 'id' => $model->category_id]), ['target' => '_blank']);
                    }

                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    if ($model->status == 0) {
                        return "На рассмотрении";
                    } else if ($model->status == 1) {
                        return "Активен";
                    } else {
                        return "Заблокирован";
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}{approve}{deny}',
                'buttons' => [
                    'approve' => function ($url, $model, $key) {
                        return Html::a("<i class = 'glyphicon glyphicon-ok'></i>", ['approve', 'id' => $model->id], ['title' => 'Одобрить']);
                    },

                    'deny' => function ($url, $model, $key) {
                        return Html::a("<i class = 'glyphicon glyphicon-remove'></i>", ['deny', 'id' => $model->id], ['title' => 'Отклонить']);
                    },
                ]
            ],

        ],
    ]); ?>
    <?php Pjax::end(); ?></div>
