<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\EducationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="education-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return Html::a($model->title, ['/report/view', 'id' => $model->id]);
                },
                'format' => 'raw'
            ],
            'date',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    if ($model->status == 1) {
                        return "Активен";
                    } else if ($model->status == 0) {
                        return "На рассмотрении";
                    } else {
                        return "Отклонен";
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}{approve}{deny}{reply}',
                'buttons' => [
                    'approve' => function ($url, $model, $key) {
                        return Html::a("<i class = 'glyphicon glyphicon-ok'></i>", ['approve', 'id' => $model->id], ['title' => 'Одобрить']);
                    },
                    'deny' => function ($url, $model, $key) {
                        return Html::a("<i class = 'glyphicon glyphicon-remove'></i>", ['deny', 'id' => $model->id], ['title' => 'Отклонить']);
                    },
                    'reply' => function ($url, $model, $key) {
                        return Html::a("<i class = 'glyphicon glyphicon-share-alt'></i>", ['reply','id' => $model->id], ['title' => 'Ответить']);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
