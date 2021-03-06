<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Politics */

$this->title = Yii::t('app', 'Редактировать: ', [
    'modelClass' => 'Politics',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Politics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="politics-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
