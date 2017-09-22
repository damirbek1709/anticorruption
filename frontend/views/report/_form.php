<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\models\Vocabulary;
use kartik\select2\Select2;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model frontend\models\Report */
/* @var $form yii\widgets\ActiveForm */

$lookups=Yii::$app->db->createCommand("SELECT * FROM vocabulary WHERE `key` LIKE 'lookup_%'")->queryAll();
$lkup=ArrayHelper::map($lookups,'key','value');
?>

<div class="report-form">

    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

    <?php echo $form->errorSummary($model)?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true,
        'placeholder' => 'Введите заголовок вашего сообщения',
        'data-toggle'=>'modal',
        'data-target'=>'#warning-modal',
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
    echo $form->field($model, 'authority_id')->widget(Select2::classname(), [
        'data' => $model->getAuthorities(),
        'hideSearch' => true,
        'options' => [
            'placeholder' => 'Выберите госорган или структуру',
            'class' => 'form-control custom-drop'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label(false); ?>

    <?php
    echo $form->field($model, 'type_id')
        ->dropDownList(ArrayHelper::map(Vocabulary::find()->asArray()->where(['key' => 'report_type'])->all(), 'id', 'value'),
            [
                'prompt' => 'Выберите тип обращения',
                'class' => 'form-control custom-drop'
            ]
        )->label(false);
    ?>




    <!-- <div class="form-group">
        <? /* echo '<label>Дата и время</label>';
        echo DateTimePicker::widget([
            'model' => $model,
            'name' => 'date',
            'attribute' => 'date',
            'options' => ['placeholder' => 'Выберите время дату и время'],
            //'convertFormat' => true,
            'pluginOptions' => [
                //'format' => 'd-M-Y g:i A',
                //'startDate' => '01-Mar-2017 12:00 AM',
                'todayHighlight' => true
            ]
        ]); */ ?>
    </div>-->


    <div class="form-group">
        <div id="user-contact" class="col-md-6" style="padding-left: 0">
            <?= $form->field($model, 'author',[
                'template' =>
                    '<div class="form-group rel">{input}<span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="'.$lkup['lookup_name'].'"></span>{error}</div>'])->textInput(['placeholder' => 'Введите ваше имя', 'class' => 'form-control sharper'])->label(false); ?>
            <?= $form->field($model, 'email',[
                'template' =>
                    '<div class="form-group rel">{input}<span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="'.$lkup['lookup_email'].'"></span>{error}</div>'])->textInput(['placeholder' => '@электронная почта', 'class' => 'form-control sharper'])->label(false); ?>
            <?= $form->field($model, 'contact',[
                'template' =>
                    '<div class="form-group rel">{input}<span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="'.$lkup['lookup_contact'].'"></span>{error}</div>'])->textInput(['placeholder' => 'Ваши контакты', 'class' => 'form-control sharper'])->label(false); ?>
        </div>

        <div class="col-md-6" style="padding-right: 0">
            <div class="border-maker anonym_height">
                <div class="radio-row">

                    <?= $form->field($model, 'anonymous', [
                        'template' => "<ul><li>{input}\n{label}\n</li></ul><div class=\"check\">
                                        <div class=\"inside\"></div>
                                    </div>",
                        'labelOptions' => ['for' => 'report-anonymous','class'=>'anon-label'],
                    ])->input('checkbox',['class'=>'input_checker','value'=>0])->label('Я хочу подать анонимное объявление') ?>
                    <span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="<?=$lkup['lookup_anonym']?>" data-placement="bottom"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <?php
    /* echo $form->field($model, 'authority_id')
         ->dropDownList(
             ArrayHelper::map(Vocabulary::find()->asArray()->where(['key'=>'report_category'])->all(), 'id', 'value')
         );*/
    ?>

    <?php
    echo $form->field($model, 'city_id')->widget(Select2::classname(), [
        'data' => $model->getDropdownItems('city'),
        'hideSearch' => true,
        'options' => ['placeholder' => 'Выберите город или регион'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label(false); ?>

    <?= $form->field($model, 'lat')->hiddenInput(['value' => 0, 'class' => 'report_lat'])->label(false); ?>
    <?= $form->field($model, 'lon')->hiddenInput(['value' => 0, 'class' => 'report_lot'])->label(false); ?>

    <div class="form-group map" id="map"></div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'send-comment btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php
    $modal = Modal::begin([
        'id' => 'warning-modal',
        'header' => Html::tag('h4', $lkup['lookup_warning_title'], ['class' => 'modal-title']),
    ]);
    echo $lkup['lookup_warning_text'];
    $modal::end();
    ?>
</div>
