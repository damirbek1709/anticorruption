<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Authority */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authority-form">

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

    <?php if(!$model->isNewRecord && $model->img):?>
    <div class="form-group">
        <label>Текущая эмблема</label>
        <div class="clear"></div>
        <?php
        $logo = Yii::getAlias("@frontend/web/images/authority/s_{$model->id}_{$model->img}");
        echo Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $logo));
        ?>
    </div>
    <?php endif;?>

    <label class="upload-label">Загрузить эмблему</label>
    <?
    echo \uitrick\yii2\widget\upload\crop\UploadCrop::widget([
        'form' => $form, 'model' => $model, 'attribute' => 'image',
        'imageOptions' => [
            'class' => 'new-image'
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
    ]); */ ?>
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