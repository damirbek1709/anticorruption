<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\models\Vocabulary;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Report */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Введите заголовок вашего сообщения', 'class' => 'form-control sharper'])->label(false); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6, 'placeholder' => 'Расскажите подробнее о факте коррупции с которым вы столкнулись', 'class' => 'form-control comment-input-text'])->label(false); ?>

    <?php
    echo $form->field($model, 'category_id')
        ->dropDownList(ArrayHelper::map(Vocabulary::find()->asArray()->where(['key' => 'report_category'])->all(), 'id', 'value'),
            [
                'prompt' => 'Выберите сектор коррупции',
                'class' => 'form-control custom-drop'
            ]
        )->label(false);
    ?>

    <?php
    echo $form->field($model, 'authority_id')->widget(Select2::classname(), [
        'data' => $model->getAuthorities(),
        'hideSearch' => true,
        'options' => [
            'placeholder' => 'Выберите госорган или структуру',
            'class'=>'form-control custom-drop'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label(false); ?>



   <!-- <div class="form-group">
        <?/* echo '<label>Дата и время</label>';
        echo DateTimePicker::widget([
            'model' => $model,
            'name' => 'date',
            'attribute' => 'date',
            'options' => ['placeholder' => 'Выберите время дату и время'],
            //'convertFormat' => true,
            'pluginOptions' => [
                //'format' => 'd-M-Y g:i A',
                //'startDate' => '01-Mar-2017 12:00 AM',
                'todayHighlight' => true
            ]
        ]); */?>
    </div>-->

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'author')->textInput(['placeholder' => 'Введите ваше имя','class' => 'form-control sharper'])->label(false); ?>
            <?= $form->field($model, 'email')->textInput(['placeholder' => '@электронная почта','class' => 'form-control sharper'])->label(false); ?>
            <?= $form->field($model, 'contact')->textInput(['placeholder' => 'Ваши контакты','class' => 'form-control sharper'])->label(false); ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'anonymous')->checkbox()->label(false) ?>
        </div>
    </div>


    <?php
    /* echo $form->field($model, 'authority_id')
         ->dropDownList(
             ArrayHelper::map(Vocabulary::find()->asArray()->where(['key'=>'report_category'])->all(), 'id', 'value')
         );*/
    ?>

    <?php
    echo $form->field($model, 'city_id')->widget(Select2::classname(), [
        'data' => $model->getDropdownItems('city'),
        'hideSearch' => true,
        'options' => ['placeholder' => 'Выберите город или регион'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label(false); ?>

    <?= $form->field($model, 'lat')->hiddenInput(['value'=>41.2044,'class' => 'report_lat'])->label(false); ?>
    <?= $form->field($model, 'lon')->hiddenInput(['value'=>74.7661,'class' => 'report_lot'])->label(false); ?>

    <div class="form-group" id="map"></div>
    <script>
        function initMap() {
            var uluru = {lat: 41.2044, lng: 74.7661};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 6,
                center: uluru
            });
            var marker = new google.maps.Marker({
                position: uluru,
                map: map,
                draggable: true
            });
            google.maps.event.addListener(marker, 'dragend', function (a) {
                $('.report_lat').val(a.latLng.lat().toFixed(4));
                $('.report_lon').val(a.latLng.lng().toFixed(4));
            });
            google.maps.event.addListener(map, 'click', function (event) {
                placeMarker(event.latLng);
                $('.report_lat').val(a.latLng.lat().toFixed(4));
                $('.report_lon').val(a.latLng.lng().toFixed(4));
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
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDyJXbc-D_sxlQgbxS6fa-ImOsz1dyyQs&callback=initMap">
    </script>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'send-comment btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
