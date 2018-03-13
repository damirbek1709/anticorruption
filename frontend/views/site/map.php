<?php

use frontend\models\Report;
use yii\helpers\Html;

?>
<div class="mobile_padder">
    <div class="main_heading">
        <?= Yii::t('app', 'Карта коррупции'); ?>
    </div>
    <div class="sort-wrap">
        <div class="sort-label">Сортировать по:</div>
        <?= Html::beginForm(['site/map'], 'get', ['id' => 'map-filter']); ?>
        <?= Html::dropDownList('authority', $authority, Report::getAuthorities(),
            [
                'onchange' => 'this.form.submit()',
                'prompt' => 'госоргану',
                'class' => 'sort-select authority-select'
            ]); ?>
        <?= Html::dropDownList('sector',
            $sector, Report::getSingleDrop('report_category'),
            [
                'onchange' => 'this.form.submit()',
                'prompt' => 'сектору коррупции',
                'class' => 'sort-select sector-select'
            ]); ?>
        <?= Html::dropDownList('city',
            $city, Report::getDropdownItems('city'),
            [
                'onchange' => 'this.form.submit()',
                'prompt' => 'местоположению',
                'class' => 'sort-select city-select'
            ]); ?>

        <?= Html::dropDownList('type',
            $type, Report::getSingleDrop('report_type'),
            [
                'onchange' => 'this.form.submit()',
                'prompt' => 'типу обращения',
                'class' => 'sort-select type-select'
            ]); ?>

        <?php echo Html::tag('div', '', ['class' => 'clear']); ?>
        <?= Html::endForm() ?>
    </div>


    <div id="map_long" authority="<?= $authority ?>" sector="<?= $sector ?>" city="<?= $city ?>" type="<?= $type ?>"
         class="map" style="height: 550px;"></div>
</div>

