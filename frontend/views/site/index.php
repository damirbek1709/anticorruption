<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\News;

?>
<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Антикоррупционный портал Кыргызской Республики');
?>
<div class="mobile_padder chameleon">
    <div class="mobile_top_links">
        <li><?=Html::a('Новости',['/news/index']);?></li>
        <li><?=Html::a('Сообщение о коррупции',['report/index']);?></li>
        <li><?=Html::a('Карта коррупции',['/site/map']);?></li>
        <li><?=Html::a('Рейтинг коррупции',['/authority/index']);?></li>
    </div>
</div>

<div class="slider-roll">
    <div class="main_heading left_floater">
        <?= Yii::t('app', 'Главные новости'); ?>
    </div>
    <div class="secondary_link">
        <?= Html::a(Yii::t('app', 'Все новости'), ['/news/index']) ?>
    </div>
    <div class="clear"></div>

    <div class="slider_wrap">
        <?php
        $news = News::find()->where(['main_news' => 1])->orderBy(['date' => SORT_DESC])->limit(3)->all();
        ?>

        <div class="demo">
            <div class="item">
                <ul id="content-slider" class="content-slider">
                    <?php
                    foreach ($news as $new) {
                        echo Html::beginTag("li", []);
                        echo Html::beginTag("div", ['class' => 'slider_cover']);
                        echo Html::beginTag("div", ['class' => 'slider_bg']);
                        echo $new->getMainImg();
                        echo Html::endTag("div");
                        echo Html::endTag("div");
                        echo Html::a($new->title, ['/news/view', 'id' => $new->id], ['class' => 'slider_title']);
                        echo Html::endTag("li");
                    }
                    ?>
                </ul>
            </div>
        </div>

    </div>
</div>
<div class="news_index_wrap">
    <div class="main_heading mobile_ribbon">
        <?= Yii::t('app', 'Лента новостей'); ?>
    </div>
    <div class="mobile_padder">
        <?php
        $news = News::find()->orderBy(['date' => SORT_DESC])->limit(3)->all();
        foreach ($news as $new) {
            echo Html::beginTag('div', ['class' => 'news_block']);
            echo Html::a($new->getThumb(), ['/news/view', 'id' => $new->id], ['class' => 'news_img']);
            echo Html::beginTag('div', ['class' => 'right_news_block']);
            echo Html::tag('span', Yii::$app->formatter->asDate($new->date), ['class' => 'news_date']);
            echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
            echo Html::beginTag('span', ['class' => 'news_view_count']);
            echo Html::tag('span', "", ['class' => 'glyphicon glyphicon-eye-open ']);
            echo Html::tag('span', "Просмотров: {$new->views}", ['style' => 'margin-left:5px']);
            echo Html::endTag('span');
            echo Html::tag('span', date("H:i", strtotime($new->date)), ['class' => 'news_date']);
            echo Html::tag('div', '', ['class' => 'clear']);
            echo Html::a($new->title, ['/news/view', 'id' => $new->id], ['class' => 'news_title']);
            echo Html::tag('div', $new->description, ['class' => 'news_description']);
            echo Html::a($new->category->value, ['/news/category', 'id' => $new->category->id], ['class' => 'news_category_link']);
            echo Html::tag('span', "<span class='comment_icon'></span> {$new->commentsCount}", ['class' => 'news_coment_span']);
            echo Html::endTag('div');
            echo Html::endTag('div');
        }
        ?>
    </div>
</div>

<div class="main_heading mobile_ribbon">
    <?= Yii::t('app', 'Карта коррупции'); ?>
</div>
<div id="map_index" class="map"></div>
<?php echo $this->render('reports') ?>




