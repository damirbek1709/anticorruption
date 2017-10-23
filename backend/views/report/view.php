<script src='https://www.google.com/recaptcha/api.js'></script>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Report */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-view">
    <div class="italic_header" style="color: #3b3b3b"><?= Html::encode($this->title) ?></div>
    <?
    echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'news_date']);
    echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
    echo Html::tag('span', Yii::$app->formatter->asTime($model->date), ['class' => 'news_date']);
    echo Html::beginTag('span', ['class' => 'news_view_count', 'style' => 'margin-left:10px;float:none']);
    echo Html::tag('span', "", ['class' => 'glyphicon glyphicon-eye-open ']);
    echo Html::tag('span', "Просмотров: {$model->views}", ['style' => 'margin-left:5px']);
    echo Html::endTag('span');
    ?>
    <div class="report-text">
        <div class="quotes"></div>
        <span class="report-padder">
            <?= $model->text; ?>
            <div class="comment-author" style="font-style: normal;font-family: 'PT Sans',sans-serif;margin-top:10px">
                <?= $model->author; ?>
            </div>
        </span>
    </div>
    <div class="report-cats">
        <div class="cat-row">
            <span><?= Yii::t('app', 'Гос.орган: ') ?></span>
            <?= $model->authority->title; ?>
        </div>
        <div class="cat-row">
            <span><?= Yii::t('app', 'Сектор коррупции: ') ?></span>
            <?= $model->department->value; ?>
        </div>

        <div class="cat-row">
            <span><?= Yii::t('app', 'Местоположение: ') ?></span>
            <?= $model->city->value; ?>
        </div>

        <div class="cat-row">
            <span><?= Yii::t('app', 'Тип обращения: ') ?></span>
            <?= $model->type->value; ?>
        </div>
    </div>


    <div class="clear" style="margin-top: 20px;"></div>

    <?php
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) {
        if ($model->status == 0) {
            echo Html::tag('span', 'Одобрить', ['class' => 'btn-moderate btn btn-success','data-status'=>1]);
        }
        else{
            echo Html::tag('span', 'Блокировать', ['class' => 'btn-moderate btn btn-danger','data-status'=>0]);
        }
    }
    ?>

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
            {lat: <?=$model->lat;?>, lng: <?=$model->lon;?>},
        ]
    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDyJXbc-D_sxlQgbxS6fa-ImOsz1dyyQs&callback=initMap">
    </script>


</div>

<script type="text/javascript">

    $('body').on('click','.btn-moderate',function () {
        var status = $(this).attr('data-status');
        var thisOne = $(this);
        $.ajax({
            url: '/report/status',
            type: 'post',
            data: {id:<?=$model->id?>,status:status},
            success: function (response) {
                if (response == 1) {
                    alert("Обращение одобрено и опубликовано  на сайте");
                    thisOne.removeClass('btn-success').addClass('btn-danger');
                    thisOne.text("Блокировать");
                    thisOne.attr('data-status',0);
                }
                else {
                    alert("Обращение заблокировано");
                    thisOne.removeClass('btn-danger').addClass('btn-success');
                    thisOne.text("Одобрить");
                    thisOne.attr('data-status',1);
                }
            }
        });

        return false;
    })
</script>
