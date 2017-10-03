<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use vova07\imperavi\Widget;
use app\models\Category;
use bupy7\cropbox\CropboxWidget;
use kartik\datetime\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'img')->hiddenInput(['value' => '','class'=>'news-main-img'])->label(false); ?>
    <?=
    $form->field($model, 'description')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
            ],
            'buttons' => ['bold', 'italic', 'orderedlist']
        ]
    ]); ?>
    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(\frontend\models\Vocabulary::find()->where(['key'=>'news_category'])->all(), 'id', 'value')
    ); ?>


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
                        "url" => "/site/remove-image",
                        'key' => basename($image),
                        'extra' => ['id' => $model->id,'controller'=>'news'],
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
                'otherActionButtons' => '
                                <button type="button" class="kv-cust-btn btn btn-xs">
                                    <i class="glyphicon glyphicon-ok"> Основной рисунок</i>
                                </button>
                                <!--<button type="button" class="kv-cust-btn btn btn-xs btn-img-remove">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>-->
                                ',
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'overwriteInitial' => false,

                'fileActionSettings' => [
                    'showZoom' => false,
                    'showRemove'=>false,
                    'indicatorNew' => '&nbsp;',
                    //'removeIcon' => '<span class="glyphicon glyphicon-trash" title="Удалить"></span> ',
                ],
            ]
        ]);
        ?>
    </div>


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

    <?= $form->field($model, 'main_news')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Сохранить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">

    var myId = $('.model_id').attr('id');
    var controller = 'news';
    var id = <?=$model->id;?>;

//    $('body').on('click','.btn-img-remove', function (e) {/*
//        e.preventDefault();
//        e.stopImmediatePropagation();*/
//        $(this).parents('.kv-preview-thumb').fadeOut();
//        var name = $(this).parents('.file-actions').siblings('.file-footer-caption').attr('title');
//        $.ajax({
//            url: "<?//=Url::base().'/site/remove-image'?>//",
//            type: "post",
//            data: {id: id,controller:controller,name:name},
//            cache: false
//        });
//    });

    $('body').on('fileselect', '#news-file',function(event, numFiles, label) {
        var name =$('.kv-preview-thumb').find('.file-footer-caption').attr('title');
        $('.news-main-img').val(name);
    });


    $('body').on('click', '.kv-cust-btn', function () {
        var name = $(this).parents('.file-actions').siblings('.file-footer-caption').attr('title');
        $('.kv-cust-btn').removeClass('btn-main-img');
        $(this).addClass('btn-main-img');
        $('.kv-cust-btn').css({'color':'#000','background-color':'#ddd'});
        $('.news-main-img').val(name);
    });
</script>

