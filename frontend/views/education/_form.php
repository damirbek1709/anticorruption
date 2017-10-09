<?php

use yii\helpers\Html;
use yii\helpers\Url;
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

    <?= $form->field($model, 'img')->hiddenInput(['value' => $model->img ? $moddel->img : '', 'class' => 'education-main-img'])->label(false); ?>

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
            if ($model->getThumbs() != null) {
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
                               <button type="button" class="kv-cust-btn btn btn-xs btn-img-remove">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                                ',
                'showCaption' => false,
                'showRemove' => false,
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

    <?php if ($model->isNewRecord) {
        $button = Yii::t('app', 'Добавить');
        $id = 0;
    } else {
        $button = "Сохранить";
        $id = $model->id;
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton($button, ['class' =>'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    var myId = $('.model_id').attr('id');
    var controller = 'education';
    var id = <?=$id?>;

    $('body').on('click', '.btn-img-remove', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $(this).parents('.kv-preview-thumb').fadeOut();

        if (id > 0) {
            var name = $(this).parents('.file-actions').siblings('.file-footer-caption').attr('title');
            $.ajax({
                url: "<?=Url::base() . '/education/remove-image'?>",
                type: "post",
                data: {id: id, controller: controller, name: name},
                cache: false
            });
        }
    });

    $('body').on('fileselect', '#education-file', function (event, numFiles, label) {
        if (id == 0) {
            $('.education-main-img').val(label);
        }
    });

    $('body').on('click', '.kv-cust-btn', function () {
        var name = $(this).parents('.file-actions').siblings('.file-footer-caption').attr('title');
        $('.kv-cust-btn').removeClass('btn-main-img');
        $(this).addClass('btn-main-img');
        $('.kv-cust-btn').css({'color': '#000', 'background-color': '#ddd'});
        $('.education-main-img').val(name);
    });
</script>

