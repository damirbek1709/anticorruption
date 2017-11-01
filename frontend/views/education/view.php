<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;?>

<?
$cssSlider = Yii::getAlias('@frontend/web')."/js/lightslider/src/css/lightslider.css";
$cssSlider = str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $cssSlider);

$jsSlider = Yii::getAlias('@frontend/web')."/js/lightslider/src/js/lightslider.js";
$jsSlider = str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $jsSlider);
?>

<link rel="stylesheet" href="<?=$cssSlider?>"/>
<script src="<?=$jsSlider?>"></script>

<?
/* @var $this yii\web\View */
/* @var $model frontend\models\Education */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Educations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view mobile_padder">

    <div class="minor_heading"><?= $this->title; ?></div>
    <?
    echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'news_date']);
    echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
    echo Html::tag('span', date("H:i",strtotime($model->date)), ['class' => 'news_date']);

    ?>
    <div class="demo" style="margin-top:10px;">
        <div class="item" style="margin-bottom:20px;">
            <ul id="content-slider" class="content-slider">
                <?php
                $images = $model->getImages();
                foreach ($images as $image) {
                    echo Html::beginTag("li", []);
                    echo Html::beginTag("div", ['class' => 'slider_cover']);
                    echo Html::beginTag("div", ['class' => 'slider_bg']);
                    echo Html::a($image, ['/education/view', 'id' => $model->id]);
                    echo Html::endTag("div");
                    echo Html::endTag("div");
                    echo Html::endTag("li");
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="news_text"><?= $model->text; ?></div>
    <div class="share_buttons">
        <span class="share_label">Поделиться в соц.сетях: </span>
        <script type="text/javascript">(function () {
                if (window.pluso) if (typeof window.pluso.start == "function") return;
                if (window.ifpluso == undefined) {
                    window.ifpluso = 1;
                    var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                    s.type = 'text/javascript';
                    s.charset = 'UTF-8';
                    s.async = true;
                    s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
                    var h = d[g]('body')[0];
                    h.appendChild(s);
                }
            })();</script>
        <div class="pluso" data-background="none;"
             data-options="medium,square,line,horizontal,counter,sepcounter=1,theme=14"
             data-services="facebook,vkontakte,odnoklassniki,twitter" data-url="http://anticorruption.kg/education/<?=$model->id?>"
             data-title="Антикоррупционный портал Кыргызской Республики">
        </div>
    </div>

</div>

<?php
$script = <<<SCRIPT
            $(document).ready(function() {
                    $("#content-slider").lightSlider({
                        loop:true,
                        keyPress:true,
                        item:1,
                        slideMargin:0,
                        adaptiveHeight:true,
                       
                    });
                    
                     $("#authority-slider").lightSlider({
                        loop:true,
                        keyPress:true,
                        item:1,
                        slideMargin:0,
                        adaptiveHeight:true,
                        pager:false,
                       
                    });
                });
                
SCRIPT;
$this->registerJs($script);
?>
