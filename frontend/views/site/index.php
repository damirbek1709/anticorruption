<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Антикоррупционный портал Кыргызской Республики');
?>

    <div class="main_heading left_floater">
        <?= Yii::t('app', 'Главные новости'); ?>
    </div>

    <div class="secondary_link">
        <?=Html::a(Yii::t('app','Все новости'),['/news/index'])?>
    </div>

    <div class="clear"></div>

    <div class="slider_wrap">
        <?php
        $news = app\models\News::find()->orderBy(['date' => SORT_DESC])->limit(3)->all();
        ?>

        <div class="demo">
            <div class="item">
                <ul id="content-slider" class="content-slider">
                    <?php
                    foreach ($news as $new){
                        if($new->main_news == 1){
                            echo Html::beginTag("li",[]);
                            echo Html::beginTag("div",['class'=>'slider_cover']);
                            echo Html::beginTag("div",['class'=>'slider_bg']);
                            echo Html::a(Html::img(Url::base()."/images/news/{$new->img}"),['/news/view','id'=>$new->id]);
                            echo Html::endTag("div");
                            echo Html::endTag("div");
                            echo Html::a($new->title,['/news/view','id'=>$new->id],['class'=>'slider_title']);
                            echo Html::endTag("li");
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>

    </div>
    <div class="news_index_wrap">
    <div class="main_heading">
        <?= Yii::t('app', 'Лента новостей'); ?>
    </div>
        <?php
        $news = app\models\News::find()->orderBy(['date' => SORT_DESC])->limit(3)->all();
        foreach ($news as $new) {
            echo Html::beginTag('div', ['class' => 'news_block']);
            echo Html::a($new->getThumb(), ['/news/view', 'id' => $new->id], ['class' => 'news_img']);
            echo Html::beginTag('div', ['class' => 'right_news_block']);
            echo Html::tag('span', Yii::$app->formatter->asDate($new->date), ['class' => 'news_date']);
            echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
            echo Html::tag('span', Yii::$app->formatter->asTime($new->date), ['class' => 'news_date']);
            echo Html::tag('div', '', ['class' => 'clear']);
            echo Html::a($new->title, ['/news/view', 'id' => $new->id], ['class' => 'news_title']);
            echo Html::tag('div', $new->description, ['class' => 'news_description']);
            echo Html::endTag('div');
            echo Html::endTag('div');
        }
        ?>
    </div>




