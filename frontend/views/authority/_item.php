<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\rating\StarRating;

echo Html::beginTag("div", ['class' => 'sidebar_slider_cover','id'=>"mark-{$model->id}"]);
echo Html::beginTag("div", ['class' => 'sidebar_slider_bg']);
if ($model->img) {
    echo Html::a(Html::img(Url::base() . "/images/authority/s_{$model->img}"), ['/authority/view', 'id' => $model->id]);
} else {
    echo Html::a(Html::img(Url::base() . "/images/site/herb.png", ['style' => 'width:150px']), ['/authority/view', 'id' => $model->id]);
}
echo Html::endTag("div");
echo StarRating::widget([
    'name' => 'rating_2',
    'value' => $model->getRating($model->id),
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
                                             $.ajax({
                                            url: \"/site/rating\",
                                            type: \"post\",
                                            data: {value:value,id:$model->id}            
                                            });             
                                             }"],
]);
echo Html::endTag("div");
echo Html::beginTag('div',['class'=>'title_width_limiter']);
echo Html::a($model->title, ['/authority/view', 'id' => $model->id], ['class' => 'authority_index_title']);
echo Html::endTag('div');
$rating=$model->getRating($model->id);
$actual_rating = $rating - 5;
if($model->getRating($model->id)==0){
    $actual_rating = "Нет оценок";
}
elseif ($rating<=5){
    $actual_rating  = $rating - 6;
}
echo Html::tag('div', "Рейтинг: <span class='inner_red'>".$actual_rating ."</span>", ['class' => 'news_date']);
echo Html::tag('div', "Оценок: <span class='inner_red'>{$model->ratingCount}</span>", ['class' => 'news_date']);
echo Html::tag('div', "Комментариев: <span class='inner_red'>{$model->commentsCount}</span>", ['class' => 'news_date']);
echo Html::tag('div', "Обращений: <span class='inner_red'>{$model->reportCount}</span>", ['class' => 'news_date']);


if($rating<5):?>
<style>
    #mark-<?=$model->id?> .rating-container .filled-stars{
        color: #b90302;
    }
</style>
<?php endif;?>

