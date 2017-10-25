<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\EducationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $news_title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="education-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Добавить новость'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'summary' => false,
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'title',
                'contentOptions' => ['style' => 'width:600px; white-space: normal;'],
            ],
            'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
