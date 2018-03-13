<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Report */

$this->title = Yii::t('app', 'Добавить обращение о коррупции');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-create mobile_padder">

    <div class="main_heading"><?= Html::encode($this->title) ?></div>
    <?php
    if (Yii::$app->user->isGuest) {
        echo Html::beginTag('p', ['class' => 'news_text', ['style' => 'font-style:italic']]);
        echo Yii::t('app', 'Внимание! Вы не авторизованы. Ваше обращение будет добавлено, но Вы не сможете удалить или редактировать его, 
а также получать уведомления о публикации или новых комментариях. Если вы хотите, чтобы в дальнейшем у вас был доступ 
к управлению обращением, то Вам следует сначала авторизоваться');
        echo Html::endTag('p');
    }
    ?>
    <p style="font-style: italic" class="news_text">

    </p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
