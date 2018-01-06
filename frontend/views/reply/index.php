<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ответи на сообщения о коррупции');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reply-index">

    <h1 class="main_heading"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'summary' => false,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'title',
        [
            'attribute' => 'report_id',
            'value' => function ($model) {
                return $model->report->title;
            }
        ],
        'date',

        ['class' => 'yii\grid\ActionColumn'],
    ],
    ]); ?>
</div>
