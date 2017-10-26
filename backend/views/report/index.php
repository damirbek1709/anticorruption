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
            'title',
            'date',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    if($model->status==1){
                        return "Активен";
                    }
                    else{
                        return "Не активен";
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'template' => '{view}{delete}',
            ],
        ],
    ]); ?>
</div>
