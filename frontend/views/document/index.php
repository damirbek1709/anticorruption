<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', $title);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">
    <div class="news-index">
        <div class="main_heading">
            <?= $title; ?>
        </div>
        

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
