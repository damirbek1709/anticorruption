<?php
use vova07\imperavi\Widget;

$title = "title{$language}";
$description = "description{$language}";
$text = "text{$language}";
?>

<?= $form->field($model, $title)->textInput(['maxlength' => true]) ?>
<?= $form->field($model, $description)->textArea(['maxlength' => true]) ?>
<?=
$form->field($model, $text)->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen'
        ]
    ]
]); ?>