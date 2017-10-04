<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Document */

$this->title = Yii::t('app', 'Добавить запись в отчеты');
?>
<div class="document-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
