<?php

use yii\helpers\Html;
use yii\helpers\BaseStringHelper; ?>

<?php
echo Html::tag('span', "Имя: <span class='inner'>{$model->author}</span>", ['class' => 'news_date right-marginer']);
echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'news_date right-marginer']);
echo Html::tag('span', "Просмотров: <span class='inner'>{$model->views}</span>", ['class' => 'news_date right-marginer']);
echo Html::tag('span', "Комментарии: <span class='inner'>{$model->commentsCount}</span>", ['class' => 'news_date right-marginer']);
echo Html::tag('div', '', ['clear' => 'both']);
echo Html::a($model->title, ['/report/view', 'id' => $model->id], ['class' => 'italic_header', 'style' => 'color:#000;margin-top:10px;display:block;']); ?>
    <div class="report-text" style="margin-top: 15px;">
        <div class="quotes"></div>
        <span class="report-padder">
            <?= BaseStringHelper::truncateWords($model->text, 25); ?>
        </span>
    </div>
    <div class="new-row clear">
        <?php
        echo Html::tag('span', "Госорган: <span class='inner_red'>{$model->authority->title}</span>", ['class' => 'news_date']);
        echo Html::tag('div', '', ['class' => 'clear']);
        echo Html::tag('span', "Сектор корупции: <span class='inner_red'>{$model->department->value}</span>", ['class' => 'news_date']);
        echo Html::tag('div', '', ['class' => 'clear']);
        echo Html::tag('span', "Тип обращения: <span class='inner_red'>{$model->type->value}</span>", ['class' => 'news_date']);
        ?>
    </div>


<? /*
    echo Html::beginTag('div', ['class' => 'right_news_block']);
    echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'news_date']);
    echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
    echo Html::beginTag('span', ['class' => 'news_view_count']);
    echo Html::tag('span', "", ['class' => 'glyphicon glyphicon-eye-open ']);
    echo Html::tag('span', "Просмотров: {$model->views}", ['style' => 'margin-left:5px']);
    echo Html::endTag('span');
    echo Html::tag('span', Yii::$app->formatter->asTime($model->date), ['class' => 'news_date']);
    echo Html::tag('div', '', ['class' => 'clear']);
    echo Html::a($model->title, ['/news/view', 'id' => $model->id], ['class' => 'news_title']);
    //echo Html::a($model->category->value, ['/news/category', 'id' => $model->category->id], ['class' =>
    'news_category_link']);
    echo Html::tag('span', 'Комментариев()', ['class' => 'news_coment_span']);
    echo Html::endTag('div');*/
?>