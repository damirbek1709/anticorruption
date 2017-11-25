<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Nav;
use frontend\models\Report;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Обращения о коррупции');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-index">
    <div class="main_heading"><?= Yii::t('app', 'Обращения о коррупции'); ?></div>
    <div class="sort-wrap">
        <div class="sort-label">Сортировать по:</div>
        <?= Html::dropDownList('authority', null, Report::getAuthorities(), ['prompt' => 'госоргану', 'class' => 'sort-select authority-select']) ?>
        <?= Html::dropDownList('sector',
            null, Report::getSingleDrop('report_category'),
            [
                'prompt' => 'сектору коррупции',
                'class' => 'sort-select sector-select'
            ]); ?>
        <?= Html::dropDownList('city',
            null, Report::getDropdownItems('city'),
            [
                'prompt' => 'местоположению',
                'class' => 'sort-select city-select'
            ]); ?>

        <?= Html::dropDownList('type',
            null, Report::getSingleDrop('report_type'),
            [
                'prompt' => 'типу обращения',
                'class' => 'sort-select type-select'
            ]); ?>

        <?php
        echo Html::tag('div', '', ['class' => 'clear']); ?>
    </div>

    <?
    echo ListView::widget([
        'summary' => false,
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'itemOptions' => [
            'class' => 'report_block',
        ],
        //'options' => ['class' => 'general-apart-list']
    ]); ?>

</div>

<script type="text/javascript">
    $('body').on('change', '.sort-select', function () {
        //alert($(this).val());
        window.location.href = "/report/" + $(this).attr('name') + "/" + $(this).val();
    })
</script>
