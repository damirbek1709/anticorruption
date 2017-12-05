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
                if (Yii::$app->user->isGuest) {
                    echo Html::a(Yii::t('app', 'Вход на сайт'), ['/user/login']);
                    echo Html::a(Yii::t('app', 'Регистрация'), ['/user/register']);
                }
                else {
                    echo Html::a(Yii::t('app', 'Выход'), ['/user/logout'], ['data-method' => 'post']);
                }
                ?>
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
            <?= Html::beginTag('a', ['href' => '/site/index']) ?>
            <div class="logo">
                <?= Yii::t('app', 'Антикоррупционный портал Кыргызской Республики'); ?>
            </div>
            <?= Html::endTag('a'); ?>

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
                echo Html::a(Yii::t('app', 'Сообщить о коррупции'), ['/report/create'], ['class' => 'report_label']);
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
            ['label' => 'Главная', 'url' => ['/site/index'],
                'linkOptions' => [
                    'style' => 'padding-left:12px'
                ],
            ],
            [
                'label' => Yii::t('app', 'Новости'),
                'items' => [
                    ['label' => Yii::t('app', 'Новостная лента'), 'url' => ['/news/category/133']],
                    ['label' => Yii::t('app', 'Сводка коррупционных преступлений'), 'url' => ['/news/category/132']],
                    ['label' => Yii::t('app', 'Достижения'), 'url' => ['/news/category/130']],
                    ['label' => Yii::t('app', 'Пресс-релизы гос.органов'), 'url' => ['/news/category/131']],
                ],
            ],
            [
                'label' => Yii::t('app', 'Антикоррупционная политика'),
                'items' => [
                    ['label' => Yii::t('app', 'Нормативно-правовые акты'), 'url' => ['/politics/category/150']],
                    ['label' => Yii::t('app', 'Международное сотрудничество'), 'url' => ['/politics/category/151']],
                ],
            ],

            [
                'label' => Yii::t('app', 'Карта коррупции'),
                'url' => ['/site/map'],
                'items' => [
                    ['label' => Yii::t('app', 'Сообщить о коррупции'), 'url' => ['report/create']],
                    ['label' => Yii::t('app', 'Рассказать о коррупционной схеме'),
                        'url' => ['/report/create'],
                        'linkOptions' => [
                            'data-method' => 'post',
                            'data-params' => ['paramType' => 137]
                        ],
                    ],
                    ['label' => Yii::t('app', 'Все обращения'), 'url' => ['report/index']],
                    ['label' => Yii::t('app', 'Мне интересно знать, Откуда?'),
                        'url' => ['/report/create'],
                        'linkOptions' => [
                            'data-method' => 'post',
                            'data-params' => ['paramType' => 138]
                        ],

                    ],
                    ['label' => Yii::t('app', 'Коррупционный рейтинг'), 'url' => ['authority/index']],
                    ['label' => Yii::t('app', 'Обращения на карте'), 'url' => ['site/map']],
                ],
            ],
            /*---------------------*/

            [
                'label' => Yii::t('app', 'Отчеты'),
                'items' => [
                    ['label' => Yii::t('app', 'Исследования'), 'url' => ['/document/category', 'id' => 146]],
                    ['label' => Yii::t('app', 'Отчеты гос.органов'), 'url' => ['/document/category', 'id' => 147]],
                   ['label' => Yii::t('app', 'Декларация о доходах'), 'url' => ['/document/category', 'id' => 149]],
                ],
            ],
            [
                'label' => Yii::t('app', 'Борьба с коррупцией'),
                'items' => [
                    ['label' => Yii::t('app', 'Профилактика коррупции в госорганах'), 'url' => ['/page/view', 'id' => 1]],
                    ['label' => Yii::t('app', 'Общественные советы'), 'url' => ['/page/view', 'id' => 2]],
                    ['label' => Yii::t('app', 'Уполномоченные по вопросам противодействия коррупции'), 'url' => ['/page/view', 'id' => 3]],
                ],
            ],
            [
                'label' => Yii::t('app', 'Антикоррупционное образование'),
                'url' => ['/education']
            ],

        ];
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'encodeLabels' => false,
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
            <?= $content ?>

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
