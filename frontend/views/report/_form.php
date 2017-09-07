<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Vocabulary;

/* @var $this yii\web\View */
/* @var $model app\models\Report */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'views')->textInput() ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?php
    echo $form->field($model, 'authority_id')
        ->dropDownList(
            ArrayHelper::map(Vocabulary::find()->asArray()->where(['key'=>'report_category'])->all(), 'id', 'value')
        );
    ?>

    <?php
    echo $form->field($model, 'category_id')
        ->dropDownList(
            ArrayHelper::map(Vocabulary::find()->asArray()->where(['key'=>'report_category'])->all(), 'id', 'value')
        );
    ?>
    <?= $form->field($model, 'lon')->textInput() ?>

    <?= $form->field($model, 'lat')->textInput() ?>

    <?php
    echo $form->field($model, 'city_id')
        ->dropDownList(
            ArrayHelper::map(Vocabulary::find()->asArray()->where(['key'=>'city'])->all(), 'id', 'value')
        );
    ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'anonymous')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
