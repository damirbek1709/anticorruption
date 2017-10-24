<?php

use yii\helpers\Html;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthoritySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Authorities');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authority-index">

    <div class="main_heading"><?= Yii::t('app','Коррупционный рейтинг госорганов') ?></div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?
    echo ListView::widget([
        'summary' => false,
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'itemOptions' => [
            'class' => 'authority_block',
        ],
        //'options' => ['class' => 'general-apart-list']
    ]); ?>
</div>
