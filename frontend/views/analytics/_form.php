<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use frontend\models\User;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model frontend\models\Analytics */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="analytics-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'text')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'imageUpload' => Yii::$app->urlManagerFrontend->createAbsoluteUrl('/site/image-upload'),
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

    <?php
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) {
        $user_data = ArrayHelper::map(User::find()->all(), 'id', 'username');
        echo $form->field($model, 'author_id')->widget(Select2::classname(), [
            'data' => $user_data,
            'options' => ['placeholder' => 'Пользователь'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        echo $form->field($model, 'status')->checkbox(['label' => 'Активен']);
    }
    else{
        $form->field($model, 'author_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false);
    }

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    var myId = $('.model_id').attr('id');
    $('.img-main').on('click', function () {

        $('.img-main').removeClass('picked-main');
        $(this).addClass('picked-main');
        var name = $(this).siblings('.thumb-img').attr('name');
        $.ajax({
            url: "/object/main",
            type: "post",
            data: {name: name, id: myId},
            cache: false
        });
    });

    $('.img-delete').on('click', function () {
        var name = $(this).siblings('.thumb-img').attr('name');
        $(this).parent().fadeOut();
        $.ajax({
            url: "/object/remove",
            type: "post",
            data: {id: myId, name: name},
            cache: false
        });
    });
</script>
