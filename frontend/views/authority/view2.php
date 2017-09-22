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
    <div class="minor_heading"><?= Html::encode($this->title) ?></div>
    <?php
    echo StarRating::widget([
        'name' => 'rating_2',
        'value' => $model->getRating($model->id),
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
            url: \"/site/rating\",
            type: \"post\",
            data: {value:value,id:$model->id}            
            });             
             }"],
    ]);
    ?>


</div>


