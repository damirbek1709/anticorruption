<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\BaseStringHelper; ?>

<?php
echo Html::a($model->getThumb(), ['/news/view', 'id' => $model->id], ['class' => 'news_img']);
echo Html::beginTag('div', ['class' => 'right_news_block']);
echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'news_date']);
echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
echo Html::beginTag('span', ['class' => 'news_view_count']);
echo Html::tag('span', "", ['class' => 'glyphicon glyphicon-eye-open ']);
echo Html::tag('span', "Просмотров: {$model->views}", ['style' => 'margin-left:5px']);
echo Html::endTag('span');
echo Html::tag('span', date("H:i",strtotime($model->date)), ['class' => 'news_date']);
echo Html::tag('div', '', ['class' => 'clear']);
echo Html::a($model->title, ['/news/view', 'id' => $model->id], ['class' => 'news_title']);
echo Html::tag('div', $model->description, ['class' => 'news_description']);
echo Html::a($model->category->value, ['/news/category', 'id' => $model->category->id], ['class' => 'news_category_link']);
echo Html::tag('span', "<span class='comment_icon'></span> {$model->commentsCount}", ['class' => 'news_coment_span']);

echo Html::endTag('div');
?>