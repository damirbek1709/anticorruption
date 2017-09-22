<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Analytics */

$this->title = Yii::t('app', 'Редактировать: ', [
    'modelClass' => 'Analytics',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Analytics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="analytics-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
