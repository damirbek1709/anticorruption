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

    <?
    echo \uitrick\yii2\widget\upload\crop\UploadCrop::widget([
        'form' => $form, 'model' => $model, 'attribute' => 'image',
        'imageOptions' => [
        ],
        'jcropOptions' => [
            'minCropBoxWidth' => 60,
            'minCropBoxHeight' => 20,
            'aspectRatio' =>44 / 28,
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
