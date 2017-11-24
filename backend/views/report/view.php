<script src='https://www.google.com/recaptcha/api.js'></script>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Report */

echo newerton\fancybox\FancyBox::widget([
    'target' => 'a[rel=fancybox]',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-view">
    <div class="italic_header" style="color: #3b3b3b"><?= Html::encode($this->title) ?></div>
    <?
    echo Html::tag('span', Yii::$app->formatter->asDate($model->date), ['class' => 'news_date']);
    echo Html::tag('span', '', ['class' => 'glyphicon glyphicon-time date-clock']);
    echo Html::tag('span', Yii::$app->formatter->asTime($model->date), ['class' => 'news_date']);
    echo Html::beginTag('span', ['class' => 'news_view_count', 'style' => 'margin-left:10px;float:none']);
    echo Html::tag('span', "", ['class' => 'glyphicon glyphicon-eye-open ']);
    echo Html::tag('span', "Просмотров: {$model->views}", ['style' => 'margin-left:5px']);
    echo Html::endTag('span');
    ?>
    <div class="report-text">
        <div class="quotes"></div>
        <span class="report-padder">
            <?= $model->text; ?>
            <div class="comment-author" style="font-style: normal;font-family: 'PT Sans',sans-serif;margin-top:10px">
                <?= $model->author; ?>
            </div>
        </span>
    </div>

    <div class="clear"></div>
    <?php $images = $model->getImages();
    foreach ($images as $key => $val) {
        echo Html::beginTag('span', ['data-thumb' => $key]);
        echo Html::a(Html::img($key), $val, ['class' => 'gallery-view-img', 'rel' => 'fancybox']);
        echo Html::endTag('span');
    }
    ?>
    <div class="report-cats">
        <div class="cat-row">
            <span><?= Yii::t('app', 'Гос.орган: ') ?></span>
            <?= $model->authority->title; ?>
        </div>
        <div class="cat-row">
            <span><?= Yii::t('app', 'Сектор коррупции: ') ?></span>
            <?= $model->department->value; ?>
        </div>

        <div class="cat-row">
            <span><?= Yii::t('app', 'Местоположение: ') ?></span>
            <?= $model->city->value; ?>
        </div>

        <div class="cat-row">
            <span><?= Yii::t('app', 'Тип обращения: ') ?></span>
            <?= $model->type->value; ?>
        </div>
    </div>


    <div class="clear" style="margin-top: 20px;"></div>

    <?php
    if ($model->lat) {
        echo Html::tag('div','',['class'=>'map','id'=>'map_view']);
        echo Html::hiddenInput('lat', $model->lat, ['class' => 'report_lat']);
        echo Html::hiddenInput('lon', $model->lon, ['class' => 'report_lon']);
    }
    echo Html::tag('div', '', ['class' => 'clear', 'style' => 'margin-top:20px']);
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) {
        if ($model->status == 0) {
            echo Html::tag('span', 'Одобрить', ['class' => 'btn-moderate btn btn-success', 'data-status' => 1]);
            echo Html::tag('span', 'Отклонить', ['class' => 'btn-moderate btn btn-danger', 'data-status' => 2]);
        }
        else if($model->status == 1) {
            echo Html::tag('span', 'Отклонить', ['class' => 'btn-moderate btn btn-danger', 'data-status' => 2]);
        }
        else{
            echo Html::tag('span', 'Одобрить', ['class' => 'btn-moderate btn btn-success', 'data-status' => 1]);
        }
    }
    ?>


</div>

<script type="text/javascript">

    $('body').on('click', '.btn-moderate', function (e) {
        e.preventDefault();
        var status = $(this).attr('data-status');
        var thisOne = $(this);
        $.ajax({
            url: 'status',
            type: 'post',
            data: {id:<?=$model->id?>, status: status, _csrf: yii.getCsrfToken()},
        });

        return false;
    })
</script>
