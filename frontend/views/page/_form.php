<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model frontend\models\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">

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

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Редактировать'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
