<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Reply */

$this->title = Yii::t('app', 'Create Reply');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Replies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reply-create">
    <?= $this->render('_form', [
        'model' => $model,
        'report'=>$report
    ]) ?>

</div>
