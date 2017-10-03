<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\News;

?>
<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Антикоррупционный портал Кыргызской Республики');
?>

<div class="main_heading left_floater">
    <?= Yii::t('app', 'Главные новости'); ?>
</div>

<div class="secondary_link">
    <?= Html::a(Yii::t('app', 'Все новости'), ['/news/index']) ?>
</div>

<div class="clear"></div>

<div class="slider_wrap">
    <?php
    $news = News::find()->where(['main_news'=>1])->orderBy(['date' => SORT_DESC])->limit(3)->all();
    ?>

    <div class="demo">
        <div class="item">
            <ul id="content-slider" class="content-slider">
                <?php
                foreach ($news as $new) {
                    if ($new->main_news == 1) {
                        echo Html::beginTag("li", []);
                        echo Html::beginTag("div", ['class' => 'slider_cover']);
                        echo Html::beginTag("div", ['class' => 'slider_bg']);
                        echo $new->getMainImg();
                        echo Html::endTag("div");
                        echo Html::endTag("div");
                        echo Html::a($new->title, ['/news/view', 'id' => $new->id], ['class' => 'slider_title']);
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
        echo Html::tag('span', Yii::$app->formatter->asTime($new->date), ['class' => 'news_date']);
        echo Html::tag('div', '', ['class' => 'clear']);
        echo Html::a($new->title, ['/news/view', 'id' => $new->id], ['class' => 'news_title']);
        echo Html::tag('div', $new->description, ['class' => 'news_description']);
        echo Html::a($new->category->value, ['/news/category', 'id' => $new->category->id], ['class' => 'news_category_link']);
        echo Html::tag('span', "Комментариев({$new->commentsCount})", ['class' => 'news_coment_span']);
        echo Html::endTag('div');
        echo Html::endTag('div');
    }
    ?>
</div>

<div class="main_heading">
    <?= Yii::t('app', 'Карта коррупции'); ?>
</div>
<div id="map"></div>
<script>

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: {lat: 41.2044, lng: 74.7661}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function (location, i) {
            return new google.maps.Marker({
                position: location,
                label: labels[i % labels.length]
            });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
    }

    var locations = [
        {lat: 42.8771, lng: 74.5698},
        {lat: 42.4782, lng: 78.3956},
    ]
</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDyJXbc-D_sxlQgbxS6fa-ImOsz1dyyQs&callback=initMap">
</script>
<?php echo $this->render('reports')?>




