<?php
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\EducationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Антикоррупционное образование');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="education-index">
    <div class="main_heading"><?= Html::encode($this->title) ?></div>
    <?php
    echo ListView::widget([
        'summary' => false,
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'itemOptions' => [
            'class' => 'news_block',
        ],
        //'options' => ['class' => 'general-apart-list']
    ]);?>
</div>
