<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\EducationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Государственные органы');
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
                'contentOptions' => ['style' => 'width:600px; white-space: normal;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{view}{delete}',
                ],
        ],
    ]); ?>
</div>
