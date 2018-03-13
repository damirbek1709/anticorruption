<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model frontend\models\Reply */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reply-form">
    <div class="italic_header" style="color: #3b3b3b"><?= Html::encode($report->title) ?></div>

    <div class="report-text">
        <div class="quotes"></div>
        <span class="report-padder">
            <?= $report->text; ?>
            <div class="comment-author" style="font-style: normal;font-family: 'PT Sans',sans-serif;margin-top:10px">
                <?= $report->author; ?>
            </div>
        </span>
    </div>

    <div class="main_heading" style="margin-top: 25px;">
        Ответить на сообщение
    </div>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'action' => ['/reply/create']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'report_id')->hiddenInput(['value' => $report->id])->label(false) ?>
    <?= $form->field($model, 'email')->hiddenInput(['value' => $report->email])->label(false) ?>

    <? echo '<label>Дата</label>';
    echo DatePicker::widget([
        'name' => 'check_issue_date',
        //'value' => date('d-M-Y', strtotime('+2 days')),
        'options' => ['placeholder' => 'Выберите дату'],
        'model' => $model,
        'name' => 'date',
        'attribute' => 'date',
        'pluginOptions' => [
            'format' => 'dd-mm-yyyy',
            'todayHighlight' => true
        ]
    ]); ?>

    <div class="form-group" style="margin-top: 15px;">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Редактировать'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
