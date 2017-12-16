<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Resistance */

$this->title = Yii::t('app', 'Добавить информацию');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Resistances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resistance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
