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

$lookups = Yii::$app->db->createCommand("SELECT * FROM vocabulary WHERE `key` LIKE 'lookup_%'")->queryAll();
$lkup = ArrayHelper::map($lookups, 'key', 'value');
?>

<div class="report-form">

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

    <div class="form-group">
        <div id="user-contact" class="col-md-6 transformer">
            <?= $form->field($model, 'author', [
                'template' =>
                    '<div class="form-group rel">{input}<span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="' . $lkup['lookup_name'] . '"></span>{error}</div>'])->textInput(['placeholder' => 'Введите ваше имя', 'class' => 'form-control sharper'])->label(false); ?>
            <?= $form->field($model, 'email', [
                'template' =>
                    '<div class="form-group rel">{input}<span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="' . $lkup['lookup_email'] . '"></span>{error}</div>'])->textInput(['placeholder' => '@электронная почта', 'class' => 'form-control sharper'])->label(false); ?>
            <?= $form->field($model, 'contact', [
                'template' =>
                    '<div class="form-group rel">{input}<span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover" data-content="' . $lkup['lookup_contact'] . '"></span>{error}</div>'])->textInput(['placeholder' => 'Ваши контакты', 'class' => 'form-control sharper'])->label(false); ?>
        </div>

        <div class="col-md-6 transformer">
            <div class="border-maker anonym_height">
                <div class="radio-row">

                    <?= $form->field($model, 'anonymous', [
                        'template' => "<ul><li>{input}\n{label}\n</li></ul><div class=\"check\">
                                        <div class=\"inside\"></div>
                                    </div>",
                        'labelOptions' => ['for' => 'report-anonymous', 'class' => 'anon-label'],
                    ])->input('checkbox', ['class' => 'input_checker', 'value' => 0])->label('Я хочу подать анонимное объявление') ?>
                    <span class="qhint glyphicon glyphicon-question-sign" data-toggle="popover" data-trigger="hover"
                          data-content="<?= $lkup['lookup_anonym'] ?>" data-placement="bottom"></span>
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
    echo $form->field($model, 'city_id')->dropDownList(\frontend\models\Report::getDropdownItems('city'), [
        'prompt' => 'Выберите регион',
        'class' => 'form-control custom-drop'
    ])->label(false);
    /*  echo $form->field($model, 'city_id')->widget(Select2::classname(), [
          'data' => $model->getDropdownItems('city'),
          'hideSearch' => true,
          'options' => ['placeholder' => 'Выберите город или регион'],
          'pluginOptions' => [
              'allowClear' => true
          ],
      ])->label(false); */ ?>


    <div class="img-drop" style="font-family: Arial,sans-serif">
        <?php
        $savedImagesCaption = [];
        if ($model->isNewRecord) {
            $savedImages = [];
        } else {
            $savedImages = $model->getThumbImages();
            $captionArr = $model->getThumbs();
            /*var_dump($captionArr);
            die();*/
            if ($model->getThumbs() != null) {
                foreach ($captionArr as $image) {
                    $savedImagesCaption[] = [
                        "caption" => basename($image),
                        "url" => "/site/remove-image",
                        'key' => basename($image),
                        'extra' => ['id' => $model->id, 'controller' => 'report'],
                    ];
                }
            }
        }
        echo $form->field($model, 'file[]')->widget(FileInput::classname(), [
            'options' => ['multiple' => true, 'accept' => 'image/*'],
            'pluginOptions' => [
                'allowedFileExtensions' => ['jpg', 'gif', 'png'],
                'initialPreview' => $savedImages,
                'initialCaption' => '',
                'uploadAsync' => false,
                //'deleteUrl'=>'/site/remove-image',
                //'data-key'=>[$savedImagesCaption,$model->id],
                'initialPreviewConfig' => $savedImagesCaption,
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'overwriteInitial' => false,

                'fileActionSettings' => [
                    'showZoom' => false,
                    'showRemove' => false,
                    'indicatorNew' => '&nbsp;',
                    //'removeIcon' => '<span class="glyphicon glyphicon-trash" title="Удалить"></span> ',
                ],
            ]
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();

        $('.warning-link').click(function (e) {
            e.preventDefault();
            $('#update-modal')
                .modal('show')
                .find('#updateModalContent')
                .load($(this).attr('value'));
        });
    });
</script>