<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $news_title;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <div class="main_heading mobile_ribbon">
        <?= $this->title; ?>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="mobile_padder">
        <?php
        echo ListView::widget([
            'summary' => false,
            'dataProvider' => $dataProvider,
            'itemView' => '_item',
            'itemOptions' => [
                'class' => 'news_block',
            ],
            //'options' => ['class' => 'general-apart-list']
        ]); ?>
    </div>
</div>
