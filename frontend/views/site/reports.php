<?php

use frontend\models\Report;
use yii\helpers\Html;
use yii\helpers\BaseStringHelper;

/**
 * Created by PhpStorm.
 * User: damir
 * Date: 14.09.2017
 * Time: 22:53
 */
?>


<div class="bn-block-col">
    <div class="bn-block type-2 margin2">
        <div class="bb-title">
            <h3 class="h3"> Сообщения о коррупции</h3>
        </div>

        <?php $reports = Report::find()->orderBy(['date' => SORT_DESC])->all();
        foreach ($reports as $report) {
            echo Html::beginTag('div', ['class' => 'report_block']);
            if($report->author){
                echo Html::tag('span', "Имя: <span class='inner'>{$report->author}</span>", ['class' => 'news_date right-marginer']);

            }
           echo Html::tag('span', Yii::$app->formatter->asDate($report->date), ['class' => 'news_date right-marginer']);
            echo Html::tag('span', "Просмотров: <span class='inner'>{$report->views}</span>", ['class' => 'news_date right-marginer']);
            echo Html::tag('span', "Комментарии: <span class='inner'>{$report->commentsCount}</span>", ['class' => 'news_date right-marginer']);
            echo Html::tag('div', '', ['clear' => 'both']);
            echo Html::a($report->title, ['/report/view', 'id' => $report->id], ['class' => 'italic_header', 'style' => 'color:#000;margin:10px 0;display:block;']);
            echo Html::tag('div','',['class'=>'quotes']);
            echo Html::beginTag('div', ['class' => 'report-text']);
            echo Html::tag('span', BaseStringHelper::truncateWords($report->text, 25), ['class' => 'report-padder']);
            echo Html::endTag('div');
            echo Html::endTag('div');

            echo Html::beginTag('div', ['class' => 'clear new-row']);
            echo Html::tag('span', "Госорган: <span class='inner_red'>{$report->authority->title}</span>", ['class' => 'news_date']);
            echo Html::tag('div', '', ['class' => 'clear']);
            echo Html::tag('span', "Сектор корупции: <span class='inner_red'>{$report->department->value}</span>", ['class' => 'news_date']);
            echo Html::tag('div', '', ['class' => 'clear']);
            echo Html::tag('span', "Тип обращения: <span class='inner_red'>{$report->type->value}</span>", ['class' => 'news_date']);
            echo Html::endTag('div');
        }
        ?>
    </div>
</div>
