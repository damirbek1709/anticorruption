<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Qwert */

$this->title = Yii::t('app', 'Create Qwert');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Qwerts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="qwert-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
