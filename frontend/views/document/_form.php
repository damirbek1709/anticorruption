<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;
use frontend\models\Vocabulary;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\Analytics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="analytics-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(Vocabulary::find()->where(['key'=>'document_category'])->all(), 'id', 'value'),
        ['prompt'=>'Выберите категорию']
    ); ?>

    <?=
    $form->field($model, 'text')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'fileUpload' => Yii::$app->urlManagerFrontend->createAbsoluteUrl('/site/file-upload'),
            'plugins' => [
                'clips',
                'fullscreen',
                'fontcolor',
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



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Редактировать'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



