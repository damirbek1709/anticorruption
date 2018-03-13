
<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\models\Vocabulary;
use kartik\file\FileInput;
use yii\bootstrap\Modal;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\Report */
/* @var $form yii\widgets\ActiveForm */

$lookups = Yii::$app->db->createCommand("SELECT * FROM vocabulary WHERE `key` LIKE 'lookup_%'")->queryAll();
$lkup = ArrayHelper::map($lookups, 'key', 'value');
?>

<div class="report-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php echo $form->errorSummary($model) ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true,
        'placeholder' => 'Введите заголовок вашего сообщения',
        'class' => 'form-control sharper'])->label(false); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6, 'placeholder' => 'Расскажите подробнее о факте коррупции с которым вы столкнулись', 'class' => 'form-control comment-input-text'])->label(false); ?>

    <?php
    echo $form->field($model, 'category_id')
        ->dropDownList(ArrayHelper::map(Vocabulary::find()->asArray()->where(['key' => 'report_category'])->all(), 'id', 'value'),
            [
                'prompt' => 'Выберите сектор коррупции',
                55=>'Другое',
                'class' => 'form-control custom-drop'
            ]
        )->label(false);
    ?>

    <?php
    echo $form->field($model, 'authority_id')->dropDownList($model->getAuthorities(), [
        'prompt' => 'Выберите госорган или структуру',
        'class' => 'form-control custom-drop'
    ])->label(false); ?>

    <?php
    $type = 134;
    if (isset($_POST['paramType'])) {
        $type = $_POST['paramType'];
    }
    echo $form->field($model, 'type_id')
        ->dropDownList(ArrayHelper::map(Vocabulary::find()->asArray()->where(['key' => 'report_type'])->all(), 'id', 'value'),
            [
                'value' => $type,
                'class' => 'form-control custom-drop'
            ]
        )->label(false);
    ?>

    <div class="form-group">
        <? echo '<label>Дата и время проишествия</label>';
        echo DateTimePicker::widget([
            'model' => $model,
            'name' => 'date',
            'attribute' => 'date',
            'options' => ['placeholder' => 'Выберите дату и время проишествия'],
            //'convertFormat' => true,
            'pluginOptions' => [
                'minView' => 1,
                //'minuteStep' => 60,
                //'format' => 'd-M-Y g:i A',
                'format' => 'yyyy-m-dd HH:ii',
                'autoclose'=>true,
                'endDate' => date('Y-m-d'),
                //'startDate' => '01-Mar-2017 12:00 AM',
                'todayHighlight' => true
            ]
        ]); ?>
    </div>


    <div class="form-group">
        <div id="user-contact" class="col-md-6 transformer">
            <?= $form->field($model, 'author', [
                'template' =>
                    '<div class="form-group rel">{input}<span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="' . $lkup['lookup_name'] . '"></span>{error}</div>'])->textInput(['placeholder' => 'Введите ваше имя', 'class' => 'form-control sharper'])->label(false); ?>
            <?= $form->field($model, 'email', [
                'template' =>
                    '<div class="form-group rel">{input}<span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="' . $lkup['lookup_email'] . '"></span>{error}</div>'])->textInput(['placeholder' => '@электронная почта', 'class' => 'form-control sharper'])->label(false); ?>
            <?= $form->field($model, 'contact', [
                'template' =>
                    '<div class="form-group rel">{input}<span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="' . $lkup['lookup_contact'] . '"></span>{error}</div>'])->textInput(['placeholder' => 'Ваши контакты', 'class' => 'form-control sharper'])->label(false); ?>
        </div>

        <div class="col-md-6 transformer">
            <div class="border-maker anonym_height">
                <div class="radio-row">

                    <?= $form->field($model, 'anonymous', [
                        'template' => "<ul><li>{input}\n{label}\n</li></ul><div class=\"check\">
                                        <div class=\"inside\"></div>
                                    </div>",
                        'labelOptions' => ['for' => 'report-anonymous', 'class' => 'anon-label'],
                    ])->input('checkbox', ['class' => 'input_checker', 'value' => 0])->label('Я хочу подать анонимное объявление') ?>
                    <span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover"
                          data-content="<?= $lkup['lookup_anonym'] ?>" data-placement="bottom"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <?php
    /* echo $form->field($model, 'authority_id')
         ->dropDownList(
             ArrayHelper::map(Vocabulary::find()->asArray()->where(['key'=>'report_category'])->all(), 'id', 'value')
         );*/
    ?>

    <?php
    echo $form->field($model, 'city_id')->dropDownList($model->getDropdownItems('city'), [
        'prompt' => 'Выберите регион',
        'class' => 'form-control custom-drop'
    ])->label(false);
    /*  echo $form->field($model, 'city_id')->widget(Select2::classname(), [
          'data' => $model->getDropdownItems('city'),
          'hideSearch' => true,
          'options' => ['placeholder' => 'Выберите город или регион'],
          'pluginOptions' => [
              'allowClear' => true
          ],
      ])->label(false); */ ?>


    <div class="img-drop" style="font-family: Arial,sans-serif">
        <?php
        $savedImagesCaption = [];
        if ($model->isNewRecord) {
            $savedImages = [];
        } else {
            $savedImages = $model->getThumbImages();
            $captionArr = $model->getThumbs();
            /*var_dump($captionArr);
            die();*/
            if ($model->getThumbs() != null) {
                foreach ($captionArr as $image) {
                    $savedImagesCaption[] = [
                        "caption" => basename($image),
                        "url" => "/site/remove-image",
                        'key' => basename($image),
                        'extra' => ['id' => $model->id, 'controller' => 'report'],
                    ];
                }
            }
        }
        echo $form->field($model, 'file[]')->widget(FileInput::classname(), [
            'options' => ['multiple' => true, 'accept' => 'image/*'],
            'pluginOptions' => [
                'allowedFileExtensions' => ['jpg', 'gif', 'png'],
                'initialPreview' => $savedImages,
                'initialCaption' => '',
                'uploadAsync' => false,
                //'deleteUrl'=>'/site/remove-image',
                //'data-key'=>[$savedImagesCaption,$model->id],
                'initialPreviewConfig' => $savedImagesCaption,
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'overwriteInitial' => false,

                'fileActionSettings' => [
                    'showZoom' => false,
                    'showRemove' => false,
                    'indicatorNew' => '&nbsp;',
                    //'removeIcon' => '<span class="glyphicon glyphicon-trash" title="Удалить"></span> ',
                ],
            ]
        ]);
        ?>
    </div>

    <?= $form->field($model, 'lat')->hiddenInput(['value' => $model->lat, 'class' => 'report_lat'])->label(false); ?>
    <?= $form->field($model, 'lon')->hiddenInput(['value' => $model->lon, 'class' => 'report_lon'])->label(false); ?>
    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false); ?>


    <div class="form-group map" id="map"></div>


    <?= Html::a(Yii::t('app', 'Предупреждение об уголовной ответственности за дачу заведомо ложных 
сообщений о совершении преступлений'), ['#'], ['class' => 'warning-link', 'id' => 'modalButton', 'data-toggle' => 'modal', 'data-target' => '#warning-modal']); ?>

    <?php
    $modal = Modal::begin([
        'header' => Html::tag('h4', Yii::t('app', 'Внимание'), ['class' => 'modal-title']),
        'id' => 'warning-modal',
        'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">' . Yii::t('app', 'Закрыть') . '</button>'

    ]);
    echo $lkup['lookup_warning_text'];
    $modal::end();
    ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'send-comment btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <?php


    /*$modal = Modal::begin([
        'id' => 'warning-modal',
        'header' => Html::tag('h4', $lkup['lookup_warning_title'], ['class' => 'modal-title']),
        'footer' => '<button type="button" class="btn btn-default" data-dismiss="modal">' . Yii::t('app', 'Закрыть') . '</button>'
    ]);
    echo $lkup['lookup_warning_text'];
    $modal::end();*/
    ?>
</div>

<script>
  /*  $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();

        $('.warning-link').click(function (e) {
            e.preventDefault();
            $('#update-modal')
                .modal('show')
                .find('#updateModalContent')
                .load($(this).attr('value'));
        });
    });
    $(window).load(function () {
        //report create anonymous check
        $('.input_checker').change(function () {
            var input = $("#user-contact input");
            if ($(this).is(":checked")) {
                input.val('');
                input.prop('disabled', true);
                $(this).val(1);
                $('.field-report-author').removeClass('has-error').find('.help-block').hide();
            }
            else {
                input.prop('disabled', false);
                $(this).val(0);
            }
        });
        //tooltip, popover

    });

    //google map
    function loadScript() {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDDyJXbc-D_sxlQgbxS6fa-ImOsz1dyyQs&callback=initMap";
        document.body.appendChild(script);
    }

    function loadScriptView() {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDDyJXbc-D_sxlQgbxS6fa-ImOsz1dyyQs&callback=initMapView";
        document.body.appendChild(script);
    }

    var map;

    function initMapView() {
        var uluru = {lat: 41.2044, lng: 74.7661};

        var defaultLat = parseFloat($('.report_lat').val());
        var defaultLon = parseFloat($('.report_lon').val());


        map = new google.maps.Map(document.getElementById('map_view'), {
            zoom: 6,
            center: uluru
        });
        var marker = new google.maps.Marker({
            map: map,
            draggable: false,
            //position:{lat: 41.2044, lng: 74.7661}

        });

        if (defaultLat != 0 && defaultLon != 0) {
            placeMarker({lat: defaultLat, lng: defaultLon});
        }

        function placeMarker(location) {
            if (marker == undefined) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    animation: google.maps.Animation.DROP,
                });
            }
            else {
                marker.setPosition(location);
            }
            //map.setCenter(location);
        }
    }


    function initMap() {
        var uluru = {lat: 41.2044, lng: 74.7661};

        var defaultLat = parseFloat($('.report_lat').val());
        var defaultLon = parseFloat($('.report_lon').val());


        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: uluru
        });
        var marker = new google.maps.Marker({
            map: map,
            draggable: true,
            //position:{lat: 41.2044, lng: 74.7661}

        });

        if (defaultLat != 0 && defaultLon != 0) {
            placeMarker({lat: defaultLat, lng: defaultLon});
        }

        google.maps.event.addListener(marker, 'dragend', function (a) {
            $('.report_lat').val(a.latLng.lat().toFixed(4));
            $('.report_lon').val(a.latLng.lng().toFixed(4));
        });
        google.maps.event.addListener(map, 'click', function (event) {
            placeMarker(event.latLng);
            $('.report_lat').val(event.latLng.lat().toFixed(4));
            $('.report_lon').val(event.latLng.lng().toFixed(4));
        });

        function placeMarker(location) {
            if (marker == undefined) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    animation: google.maps.Animation.DROP,
                });
            }
            else {
                marker.setPosition(location);
            }
            //map.setCenter(location);
        }
    }

    function newLocation(newLat, newLng) {
        map.setCenter({
            lat: newLat,
            lng: newLng
        });
    }

    function newZoom(level) {
        map.setZoom(level);
    }

    $(window).load(function () {
        loadScript();
        var imported = document.createElement('script');
        imported.src = '/js/cities.js';
        document.body.appendChild(imported);
    });

    $('#report-city_id').change(function () {
        console.log("changed");
        var city_id = $(this).val();
        var coord = getCityCoord(city_id);

        console.log(coord);
        newLocation(coord[0], coord[1]);
        newZoom(13);
    });*/

</script>
