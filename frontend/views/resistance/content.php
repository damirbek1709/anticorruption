<?php
use vova07\imperavi\Widget;

$title = "title{$language}";
$text = "text{$language}";
?>

<?= $form->field($model, $title)->textInput(['maxlength' => true]) ?>

<?=
$form->field($model, $text)->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'imageUpload' => Yii::$app->urlManagerFrontend->createAbsoluteUrl('/site/image-upload'),
        'plugins' => [
            'clips',
            'fullscreen',
            'table',
        ]
    ]
]); ?>