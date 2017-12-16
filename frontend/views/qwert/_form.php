<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use frontend\models\Vocabulary;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model frontend\models\Qwert */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="qwert-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true,
        'placeholder' => 'Введите заголовок вашего сообщения',
        'class' => 'form-control sharper'])->label(false); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6, 'placeholder' => 'Расскажите подробнее о факте коррупции с которым вы столкнулись', 'class' => 'form-control comment-input-text'])->label(false); ?>

    <?php
    echo $form->field($model, 'category_id')
        ->dropDownList(ArrayHelper::map(Vocabulary::find()->asArray()->where(['key' => 'report_category'])->all(), 'id', 'value'),
            [
                'prompt' => 'Выберите сектор коррупции',
                'class' => 'form-control custom-drop'
            ]
        )->label(false);
    ?>

    <?php
    echo $form->field($model, 'authority_id')->dropDownList(\frontend\models\Report::getAuthorities(), [
        'prompt' => 'Выберите госорган или структуру',
        'class' => 'form-control custom-drop'
    ])->label(false); ?>

    <?php
    $type = 134;
    if (isset($_POST['paramType'])) {
        $type = $_POST['paramType'];
    }
    echo $form->field($model, 'type_id')
        ->dropDownList(ArrayHelper::map(Vocabulary::find()->asArray()->where(['key' => 'report_type'])->all(), 'id', 'value'),
            [
                'value' => $type,
                'prompt' => 'Выберите тип обращения',
                'class' => 'form-control custom-drop'
            ]
        )->label(false);
    ?>

    <div class="form-group">
        <? echo '<label>Дата и время</label>';
        echo DateTimePicker::widget([
            'model' => $model,
            'name' => 'date',
            'attribute' => 'date',
            'options' => ['placeholder' => 'Выберите время дату и время'],
            //'convertFormat' => true,
            'pluginOptions' => [
                //'minView' => 0,
                'minuteStep' => 30,
                //'format' => 'd-M-Y g:i A',
                'format' => 'yyyy-m-dd HH:ii',
                //'startDate' => '01-Mar-2017 12:00 AM',
                'todayHighlight' => true
            ]
        ]); ?>
    </div>














    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'views')->textInput() ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'authority_id')->textInput() ?>

    <?= $form->field($model, 'category_id')->textInput() ?>

    <?= $form->field($model, 'lon')->textInput() ?>

    <?= $form->field($model, 'lat')->textInput() ?>

    <?= $form->field($model, 'city_id')->textInput() ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'anonymous')->textInput() ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
