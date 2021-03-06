<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Politics */

$this->title = Yii::t('app', 'Добавить запись в раздел Антикоррупционная политика');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Politics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="politics-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
