<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Document */

$this->title = Yii::t('app', 'Редактировать ', [
    'modelClass' => 'Document',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="document-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
