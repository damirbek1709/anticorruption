<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use kartik\rating\StarRating;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic,cyrillic-ext"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,700&amp;subset=cyrillic" rel="stylesheet">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<link rel="stylesheet" href="<?= Url::base() ?>/js/lightslider/src/css/lightslider.css"/>
<script src="<?= Url::base() ?>/js/lightslider/src/js/lightslider.js"></script>

<div class="wrap">
    <div class="top_header">
        <div class="width_limiter">
            <div class="left_top_header">
                <?= Html::a(Yii::t('app', 'О проекте'), ['/site/about']) ?>
                <?= Html::a(Yii::t('app', 'Контакты'), ['/site/contact']) ?>
                <?= Html::a(Yii::t('app', 'Обратная связь'), ['/site/feedback']) ?>
                <?php
                if(Yii::$app->user->isGuest)
                    echo Html::a(Yii::t('app', 'Вход на сайт'), ['/user/login']);
                else{
                    echo Html::a(Yii::t('app', 'Выход'), ['/user/logout'],['data-method'=>'post']);
                }
                ?>
                <?= Html::a(Yii::t('app', 'Регистрация'), ['/user/register']) ?>
            </div>


            <div class="right_top_header">
                <?= Html::a(Html::tag('span', ''), 'http://store.apple.com', ['class' => 'apple_icon']); ?>
                <?= Html::a(Html::tag('span', ''), 'http://play.google.com', ['class' => 'android_icon']); ?>
                <?= Html::a(Html::tag('span', ''), 'http://facebook.com', ['class' => 'fb_icon']); ?>
            </div>
        </div>
    </div>

    <div class="main_header">
        <div class="centralizer">
            <div class="logo">
                <?= Yii::t('app', 'Антикоррупционный портал Кыргызской Республики'); ?>
            </div>

            <div class="report_header">
                <?php
                $separator = ' ';
                $formated_num = preg_replace('/(?<=\d)\x' . bin2hex($separator[0]) . '(?=\d)/',
                    $separator,
                    number_format(12967, 0, '.', $separator));
                ?>
                <?= Html::tag('div', $formated_num . ' ' . Yii::t('app', 'обращений о коррупции'), ['class' => 'report_number']); ?>
                <?
                echo Html::beginTag('button', ['class' => 'button_transparent']);
                echo Html::tag('span', Yii::t('app', 'Сообщить о коррупции'), ['class' => 'report_label']);
                echo Html::tag('span', Yii::t('app', ''), ['class' => 'report_arrow']);
                echo Html::endTag('button');
                ?>
            </div>
        </div>
    </div>

    <div class="top-menu">
        <?php
        NavBar::begin([
            'brandLabel' => '',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse',
            ],
        ]);
        $menuItems = [
            ['label' => Yii::t('app', 'Главная'), 'url' => ['/site/index']],
            [
                'label' => Yii::t('app', 'Новости'),
                'items' => [
                    ['label' => Yii::t('app', 'Новостная лента'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Сводка коррупционных преступлений'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Достижения'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Пресс-релизы гос.органов'), 'url' => '#'],
                ],
            ],
            [
                'label' => Yii::t('app', 'Антикоррупционное образование'),
                'items' => [
                    ['label' => Yii::t('app', 'Новостная лента'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Сводка коррупционных преступлений'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Достижения'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Пресс-релизы гос.органов'), 'url' => '#'],
                ],
            ],
            [
                'label' => Yii::t('app', 'Отчеты'),
                'items' => [
                    ['label' => Yii::t('app', 'Новостная лента'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Сводка коррупционных преступлений'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Достижения'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Пресс-релизы гос.органов'), 'url' => '#'],
                ],
            ],
            [
                'label' => Yii::t('app', 'Борьба с коррупцией'),
                'items' => [
                    ['label' => Yii::t('app', 'Новостная лента'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Сводка коррупционных преступлений'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Достижения'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Пресс-релизы гос.органов'), 'url' => '#'],
                ],
            ],
            [
                'label' => Yii::t('app', 'Карта коррупции'),
                'items' => [
                    ['label' => Yii::t('app', 'Новостная лента'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Сводка коррупционных преступлений'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Достижения'), 'url' => '#'],
                    ['label' => Yii::t('app', 'Пресс-релизы гос.органов'), 'url' => '#'],
                ],
            ],

        ];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => $menuItems,
        ]);
        NavBar::end();
        ?>
    </div>


    <div class="container">
        <div class="width_limiter">
            <?
            /*echo  Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ])*/ ?>
            <?= Alert::widget() ?>

            <div class="central_block"><?= $content ?></div>

        </div>
    </div>
</div>
</div>

<footer class="footer">
    <div class="width_limiter">
        <div class="pull-left">
            <?= Html::a(Html::tag('div', ''), 'http://un.org.kg', ['class' => 'un_link']); ?>
        </div>

        <div class="pull-center">© <?= date('Y'); ?> Антикоррупционный Портал <br> Кыргызской Республики</div>

        <div class="pull-right">
            <?= Html::a(Html::tag('div', ''), 'http://play.google.com', ['class' => 'android_footer_icon']); ?>
            <?= Html::a(Html::tag('div', ''), 'http://store.apple.com', ['class' => 'apple_footer_icon']); ?>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
