<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\models\Page;

$model = Page::findOne(5);
$model->translate(Yii::$app->language);
$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <div class="main_heading mobile_ribbon"><?=Yii::t('app',$this->title);?></div>
    <div class="news_text"><?=$model->text;?></div>
</div>
