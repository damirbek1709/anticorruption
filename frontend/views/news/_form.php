<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\datetime\DateTimePicker;
use app\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]]); ?>

    <?php
    echo Tabs::widget([
        'items' => [
            [
                'label' => 'Контент на русском',
                'content' => $this->render('content', ['model' => $model, 'language' => '','form'=>$form]),
            ],
            [
                'label' => 'Контент на кыргызском',
                'content' => $this->render('content', ['model' => $model,'language'=>'_ky','form'=>$form]),
                'options' => ['tag' => 'div'],
            ],
            [
                'label' => 'Контент на английском',
                'content' => $this->render('content', ['model' => $model,'language'=>'_en','form'=>$form]),
                'options' => ['tag' => 'div'],
            ],

        ],
        'options' => ['tag' => 'div'],
        'itemOptions' => ['tag' => 'div'],
        'headerOptions' => ['class' => 'my-class'],
        'clientOptions' => ['collapsible' => false],
    ]);
    ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(\frontend\models\Vocabulary::find()->where(['key'=>'news_category'])->all(), 'id', 'value')
    ); ?>


    <?= $form->field($model, 'img')->hiddenInput(['value' => $model->img ? $model->img : '','class'=>'news-main-img'])->label(false); ?>


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
                'deleteUrl'=>Yii::$app->urlManagerFrontend->createAbsoluteUrl(['site/remove-image']),
                'data-name'=>[$savedImagesCaption,$model->id],
                'initialPreviewConfig' => $savedImagesCaption,
                'otherActionButtons' => '
                                <button type="button" class="kv-cust-btn btn btn-xs">
                                    <i class="glyphicon glyphicon-ok"> Основной рисунок</i>
                                </button>
                                
                                ',
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'overwriteInitial' => false,

                'fileActionSettings' => [
                    'showZoom' => false,
                    'showRemove'=>true,
                    'indicatorNew' => '&nbsp;',
                    //'removeIcon' => '<span class="glyphicon glyphicon-trash" title="Удалить"></span> ',
                ],
            ]
        ]);
        ?>
    </div>



    <div class="form-group">
        <? echo '<label>Установите дату и время</label>';
        //echo date("Y-m-d h:i");
        $current_date = $model->date;
        if($model->isNewRecord){
            $current_date = date("Y-m-d H:i:s");
        }
        echo DateTimePicker::widget([
            'model' => $model,
            'name' => 'date',
            'attribute' => 'date',
            'value'=> $current_date,
            'options' => ['placeholder' => $current_date],
            //'convertFormat' => true,
            'pluginOptions' => [
                //'format' => 'yyyy-mm-dd H:i',
                //'startDate' => '01-Mar-2017 12:00 AM',
                'todayHighlight' => true
            ]
        ]); ?>
    </div>

    <?= $form->field($model, 'main_news')->checkbox() ?>

    <?php if($model->isNewRecord){
        $button = Yii::t('app', 'Добавить');
        $id = 0;
    }
    else{
        $button = "Сохранить";
        $id = $model->id;
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton($button, ['class' =>'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    var myId = $('.model_id').attr('id');
    var controller = 'news';
    var id = <?=$id?>;

    $('body').on('click','.btn-img-remove', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        $(this).parents('.kv-preview-thumb').fadeOut();

        if(id>0) {
            var name = $(this).parents('.file-actions').siblings('.file-footer-caption').attr('title');
            $.ajax({
                url: "<?=Yii::$app->urlManagerFrontend->createAbsoluteUrl(['site/remove-image']);?>",
                type: "post",
                data: {id: id, controller: controller, name: name},
                cache: false
            });
        }
    });

    $('body').on('fileselect', '#news-file',function(event, numFiles, label) {
        if(id==0) {
            $('.news-main-img').val(label);
        }
    });

    $('body').on('click', '.kv-cust-btn', function () {
        var name = $(this).parents('.file-actions').siblings('.file-footer-caption').attr('title');
        $('.kv-cust-btn').removeClass('btn-main-img');
        $(this).addClass('btn-main-img');
        $('.kv-cust-btn').css({'color':'#000','background-color':'#ddd'});
        $('.news-main-img').val(name);
    });
</script>

