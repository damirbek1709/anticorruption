<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;
use frontend\models\Vocabulary;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model frontend\models\Analytics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="analytics-form">

    <?php $form = ActiveForm::begin(); ?>

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
        ArrayHelper::map(Vocabulary::find()->where(['key'=>'politics_category'])->all(), 'id', 'value'),
        ['prompt'=>'Выберите категорию']
    ); ?>


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



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Редактировать'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



