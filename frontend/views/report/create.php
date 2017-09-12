<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Report */

$this->title = Yii::t('app', 'Добавить обращение о коррупции');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-create">

    <div class="main_heading"><?= Html::encode($this->title) ?></div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
