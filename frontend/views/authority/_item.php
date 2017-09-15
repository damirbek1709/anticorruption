<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\rating\StarRating;

echo Html::beginTag("div", ['class' => 'sidebar_slider_cover']);
echo Html::beginTag("div", ['class' => 'sidebar_slider_bg']);
if ($model->img) {
    echo Html::a(Html::img(Url::base() . "/images/authority/s_{$model->img}"), ['/authority/view', 'id' => $model->id]);
} else {
    echo Html::a(Html::img(Url::base() . "/images/site/herb.png", ['style' => 'width:150px']), ['/authority/view', 'id' => $model->id]);
}
echo Html::endTag("div");
echo Html::endTag("div");
echo StarRating::widget([
    'name' => 'rating_2',
    'value' => $model->rating,
    'pluginOptions' => [
        'showClear' => false,
        'showCaption' => false,
        'size' => 'xs',
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
echo Html::beginTag('div',['class'=>'title_width_limiter']);
echo Html::a($model->title, ['/authority/view', 'id' => $model->id], ['class' => 'authority_index_title']);
echo Html::endTag('div');
echo Html::tag('div', "Рейтинг: <span class='inner_red'>{$model->rating}</span>", ['class' => 'news_date']);
echo Html::tag('div', "Оценок: <span class='inner_red'>{$model->votes}</span>", ['class' => 'news_date']);
echo Html::tag('div', "Комментариев: <span class='inner_red'></span>", ['class' => 'news_date']);
echo Html::tag('div', "Обращений: <span class='inner_red'></span>", ['class' => 'news_date']);
?>

