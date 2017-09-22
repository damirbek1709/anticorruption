<script src='https://www.google.com/recaptcha/api.js'></script>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\rating\StarRating;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Authority */

$this->title = $model->title;;
?>
<div class="news-view">
    <div class="minor_heading"><?= Html::encode($this->title) ?></div>
    <div class="authority-logo">
        <div class="rating-authority-bg">
            <?php
            if ($model->img)
                echo Html::img(Url::base() . '/images/authority/' . $model->img);
            else
                echo Html::img(Url::base() . '/images/authority/herb.png');
            ?>
            <?php
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
            ?>
        </div>
    </div>

    <div class="news_text"><?= $model->text ?></div>
    <div class="tabs-authority">
        <?php
        echo Tabs::widget([
            'items' => [
                [
                    'label' => 'Комментарии',
                    'content' => $this->render('comments',['model'=>$model,'comment'=>$comment]),
                ],
                [
                    'label' => 'Обращения',
                    'content' => 'Sed non urna. Phasellus eu ligula. Vestibulum sit amet purus...',
                    'options' => ['tag' => 'div'],
                ],
                [
                    'label' => 'Сводка',
                    'content' => 'Morbi tincidunt, dui sit amet facilisis feugiat...',
                    'options' => ['id' => 'my-tab'],
                ],

            ],
            'options' => ['tag' => 'div'],
            'itemOptions' => ['tag' => 'div'],
            'headerOptions' => ['class' => 'my-class'],
            'clientOptions' => ['collapsible' => false],
        ]);

        ?>
    </div>

</div>

<?php if ($model->getRating($model->id) < 5): ?>
    <style>
        .rating-container .filled-stars {
            color: #b90302;
        }
    </style>
<?php endif; ?>