<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\QwertSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="qwert-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'views') ?>

    <?= $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'authority_id') ?>

    <?php // echo $form->field($model, 'category_id') ?>

    <?php // echo $form->field($model, 'lon') ?>

    <?php // echo $form->field($model, 'lat') ?>

    <?php // echo $form->field($model, 'city_id') ?>

    <?php // echo $form->field($model, 'text') ?>

    <?php // echo $form->field($model, 'anonymous') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'contact') ?>

    <?php // echo $form->field($model, 'type_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>