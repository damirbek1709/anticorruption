<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\BaseStringHelper; ?>

<?php
echo Html::beginTag('div', ['class' => 'right_news_block']);
echo Html::tag('div', '', ['class' => 'clear']);
echo Html::a($model->title, ['/resistance/view', 'id' => $model->id], ['class' => 'news_title']);
//echo Html::tag('div', BaseStringHelper::truncateWords(strip_tags($model->text),20), ['class' => 'news_description']);
echo Html::endTag('div');
?>