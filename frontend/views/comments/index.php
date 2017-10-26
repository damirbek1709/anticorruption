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

            'id',
            'name',
            'date',
            'email:email',
            'text:ntext',
            [
                'format' => 'raw',
                'attribute' => 'material',
                'contentOptions' => ['style' => 'width:200px; white-space: normal;'],
                'value' => function ($model) {
                    if ($model->report_id) {
                        return Html::a($model->report->title, Yii::$app->urlManagerFrontend->createAbsoluteUrl(['report/view', 'id' => $model->report_id]));
                    } elseif ($model->news_id) {
                        return Html::a($model->news->title, Yii::$app->urlManagerFrontend->createAbsoluteUrl(['news/view', 'id' => $model->news_id]));
                    } else {
                        return Html::a($model->authority->title, Yii::$app->urlManagerFrontend->createAbsoluteUrl(['authority/view', 'id' => $model->category_id]));
                    }

                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?></div>
