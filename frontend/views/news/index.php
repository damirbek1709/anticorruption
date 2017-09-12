<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <div class="main_heading">
        <?= Yii::t('app', 'Лента новостей'); ?>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    echo ListView::widget([
        'summary' => false,
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'itemOptions' => [
            'class' => 'news_block',
        ],
        //'options' => ['class' => 'general-apart-list']
    ]);?>
</div>
