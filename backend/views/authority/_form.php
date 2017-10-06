<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\rating\StarRating;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\Authority */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authority-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]]); ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <label class="upload-label">Загрузить эмблему</label>
    <?
    echo \uitrick\yii2\widget\upload\crop\UploadCrop::widget([
        'form' => $form, 'model' => $model, 'attribute' => 'image',
        'imageOptions' => [
                'class'=>'new-image'
        ],
        'jcropOptions' => [
            'minCropBoxWidth' => 60,
            'minCropBoxHeight' => 20,
            'aspectRatio' => 1 / 1,
            'checkCrossOrigin' => false,
            'zoomOnWheel' => true
        ],
    ]);
    ?>

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


    <?php /*echo $form->field($model, 'rating')->widget(StarRating::classname(), [
        'pluginOptions' => [
            'theme' => 'krajee-uni',
            'filledStar' => '&#x2605;',
            'emptyStar' => '&#x2606;',
            'step' => 1,
            'starCaptions' => [
                1 => 'Very Poor',
                2 => 'Poor',
                3 => 'Ok',
                4 => 'Good',
                5 => 'Very Good',
            ],
        ]
    ]); */?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Сохранить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        var newest = $('.field-authority-image').find('.control-label').text('Выбрать рисунок');
    })


</script>