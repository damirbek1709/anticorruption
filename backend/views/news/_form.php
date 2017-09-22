<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\Analytics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="analytics-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'img')->hiddenInput(['value' => '','class'=>'educattion-main-img']); ?>

    <?=
    $form->field($model, 'text')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
                'clips',
                'fullscreen'
            ]
        ]
    ]); ?>

    <div class="form-group">
        <? echo '<label>Дата и время</label>';
        echo DateTimePicker::widget([
            'model' => $model,
            'name' => 'date',
            'attribute' => 'date',
            'options' => ['placeholder' => 'Установите дату и время'],
            //'convertFormat' => true,
            'pluginOptions' => [
                //'format' => 'd-M-Y g:i A',
                //'startDate' => '01-Mar-2017 12:00 AM',
                'todayHighlight' => true
            ]
        ]); ?>
    </div>

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
            if ($model->getThumbs()!=null)
            {
                foreach ($captionArr as $image) {
                    $savedImagesCaption[] = [
                        "caption" => basename($image),
                        "url" => "remove-image",
                        'key' => basename($image),
                        'extra' => ['id' => $model->id],
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
                //'deleteUrl'=>'/ads/remove-image',
                //'data-key'=>[$savedImagesCaption,$model->id],
                'initialPreviewConfig' => $savedImagesCaption,
                'otherActionButtons' => '
                                <button type="button" class="kv-cust-btn btn btn-xs">
                                    <i class="glyphicon glyphicon-ok"> Основной рисунок</i>
                                </button>
                               <!-- <button type="button" class="kv-cust-btn btn btn-xs">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>-->
                                ',
                'showCaption' => false,
                'showRemove' => true,
                'showUpload' => false,
                'overwriteInitial' => false,
                'fileActionSettings' => [
                    'showZoom' => false,
                    'indicatorNew' => '&nbsp;',
                    'removeIcon' => '<span class="glyphicon glyphicon-trash" title="Удалить"></span> ',
                ],
            ]
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    var myId = $('.model_id').attr('id');
    $('.img-main').on('click', function () {

        $('.img-main').removeClass('picked-main');
        $(this).addClass('picked-main');
        var name = $(this).siblings('.thumb-img').attr('name');
        $.ajax({
            url: "/object/main",
            type: "post",
            data: {name: name, id: myId},
            cache: false
        });
    });

    $('.img-delete').on('click', function () {
        var name = $(this).siblings('.thumb-img').attr('name');
        $(this).parent().fadeOut();
        $.ajax({
            url: "/object/remove",
            type: "post",
            data: {id: myId, name: name},
            cache: false
        });
    });


    $('body').on('click', '.kv-cust-btn', function () {
        var name = $(this).parents('.file-actions').siblings('.file-footer-caption').attr('title');
        $('.kv-cust-btn').removeClass('btn-main-img');
        $(this).addClass('btn-main-img');
        $('.kv-cust-btn').css({'color':'#000','background-color':'#ddd'});
        $('.educattion-main-img').val(name);
    });
</script>

