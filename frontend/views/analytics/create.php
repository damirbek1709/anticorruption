<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Analytics */

$this->title = Yii::t('app', 'Добавить запись');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Analytics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="analytics-create">

    <div class="main_heading"><?= Html::encode($this->title) ?></div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
