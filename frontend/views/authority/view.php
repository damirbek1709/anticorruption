<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\rating\StarRating;

/* @var $this yii\web\View */
/* @var $model app\models\Authority */
?>


<?
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Authorities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authority-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>



    <?php
    echo StarRating::widget([
        'name' => 'rating_2',
        'value' => $model->rating - 5,
        'pluginOptions' => [
            'size'=>'xs',
            'stars' => 10,
            'min' => 0,
            'max' => 10,
            'step' => 1,
            'starCaptions' => [
                1 => 'Very Poor',
                2 => 'Poor',
                3 => 'Ok',
                4 => 'Good',
                5 => 'Very Good',
                6 => 'Very Good',
                7 => 'Very Good',
                8 => 'Very Good',
                9 => 'Very Good',
                10 => 'Very Good',
            ],
        ],

        'pluginEvents' => [
            "rating.change" => "function(event, value, caption) {
             alert(value); 
             $.ajax({
            url: \"/frontend/web/site/rating\",
            type: \"post\",
            data: {value:value,id:$model->id}            
            });             
             }"],
    ]);
    ?>
</div>


