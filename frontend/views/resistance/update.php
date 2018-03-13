<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Resistance */

$this->title = Yii::t('app', 'Редактировать: ', [
    'modelClass' => 'Resistance',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Resistances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="resistance-update">

    <div class="main_heading"><?= Html::encode($this->title) ?></div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
