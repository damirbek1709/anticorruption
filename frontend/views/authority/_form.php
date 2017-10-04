<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\rating\StarRating;

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

    <?
    echo \uitrick\yii2\widget\upload\crop\UploadCrop::widget([
        'form' => $form, 'model' => $model, 'attribute' => 'image',
        'imageOptions' => [
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

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>


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
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
