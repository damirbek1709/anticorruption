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
use frontend\models\News;
use frontend\models\Authority;
use frontend\models\Analytics;

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
                if (Yii::$app->user->isGuest)
                    echo Html::a(Yii::t('app', 'Вход на сайт'), ['/user/login']);
                else {
                    echo Html::a(Yii::t('app', 'Выход'), ['/user/logout'], ['data-method' => 'post']);
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
            ['label' => Yii::t('app', 'Главная'), 'url' => ['/site/index']],
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
                'label' => Yii::t('app', 'Антикоррупционное образование'),
                'url' => ['/education']
            ],
            [
                'label' => Yii::t('app', 'Отчеты'),
                'items' => [
                    ['label' => Yii::t('app', 'Исследования'), 'url' => ['/document/category', 'id' => 146]],
                    ['label' => Yii::t('app', 'Отчеты гос.органов'), 'url' => ['/document/category', 'id' => 147]],
                    ['label' => Yii::t('app', 'Международное сотрудничество'), 'url' => ['/document/category', 'id' => 148]],
                    ['label' => Yii::t('app', 'Декларация о доходах'), 'url' => ['/document/category', 'id' => 149]],
                ],
            ],
            [
                'label' => Yii::t('app', 'Борьба с коррупцией'),
                'items' => [
                    ['label' => Yii::t('app', 'Профилактика коррупции в госорганах'), 'url' => ['/page/view', 'id' => 1]],
                    ['label' => Yii::t('app', 'Общественные советы'), 'url' => ['/page/view', 'id' => 2]],
                    ['label' => Yii::t('app', 'Комплайенс-офицеры'), 'url' => ['/page/view', 'id' => 3]],
                ],
            ],
            [
                'label' => Yii::t('app', 'Карта коррупции'),
                'url' => ['/site/map'],
                'items' => [
                    ['label' => Yii::t('app', 'Сообщить о коррупции'), 'url' => ['report/create']],
                    ['label' => Yii::t('app', 'Рассказать о коррупционной схеме'),
                        'url' => ['report/create'],
                        'data-method' => 'POST',
                        'data-params' => ['param' => 1],
                    ],
                    ['label' => Yii::t('app', 'Все обращения'), 'url' => ['report/index']],
                    ['label' => Yii::t('app', 'Мне интересно знать, Откуда?'), 'url' => ['report/create']],
                    ['label' => Yii::t('app', 'Коррупционный рейтинг'), 'url' => ['authority/index']],
                    ['label' => Yii::t('app', 'Обращения на карте'), 'url' => ['site/map']],
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
            <div class="left_block">
                <div class="news_categories">
                    <div class="padder">
                        <div class="italic_header">
                            <?= Yii::t('app', 'Новости'); ?>
                        </div>
                        <?= Html::tag("span", "", ["class" => "l-list l-news-list"]); ?>
                        <?= Html::tag("div", Html::a(Yii::t('app', 'Новостная лента'), ['/news/category', 'id' => 133]), ["class" => "left_category_list"]); ?>
                        <?= Html::tag("span", "", ["class" => "l-list l-victim-list"]); ?>
                        <?= Html::tag("div", Html::a(Yii::t('app', 'Сводка коррупционных преступлений'), ['/news/category', 'id' => 132]), ["class" => "left_category_list"]); ?>
                        <?= Html::tag("span", "", ["class" => "l-list l-achiev-list"]); ?>
                        <?= Html::tag("div", Html::a(Yii::t('app', 'Достижения'), ['/news/category', 'id' => 130]), ["class" => "left_category_list"]); ?>
                        <?= Html::tag("span", "", ["class" => "l-list l-press-list"]); ?>
                        <?= Html::tag("div", Html::a(Yii::t('app', 'Пресс-релизы гос.органов'), ['/news/category', 'id' => 131], ['class' => 'rmb']), ["class" => "left_category_list"]); ?>
                    </div>
                </div>
                <div class="l_report_block">
                    <?= Html::a(Yii::t('app', 'Исследования'), ['#'], ['class' => 'report_link']); ?>
                    <?= Html::a(Yii::t('app', 'Отчеты госорганов'), ['#'], ['class' => 'report_link']); ?>
                    <?= Html::a(Yii::t('app', 'Международное сотрудничество'), ['#'], ['class' => 'report_link']); ?>
                    <?= Html::a(Yii::t('app', 'Декларация о доходах'), ['#'], ['class' => 'report_link']); ?>
                </div>

                <div class="l_corruption_block">
                    <div class="padder">
                        <div class="italic_header">
                            <?= Yii::t('app', 'Борьба с коррупцией'); ?>
                        </div>
                        <div class="l_corruption_links">
                            <?= Html::a(Yii::t('app', 'Профилактика коррупции в госорганах'), ['#']) ?>
                            <?= Html::a(Yii::t('app', 'Общественные советы'), ['#']) ?>
                            <?= Html::a(Yii::t('app', 'Комплайенс-офицеры'), ['#']) ?>
                        </div>
                        <div class="italic_header top_marginer">
                            <?= Yii::t('app', 'Карта коррупции'); ?>
                        </div>
                        <div class="l_corruption_links">
                            <?= Html::a(Yii::t('app', 'Сообщить о коррупции'), ['#']) ?>
                            <?= Html::a(Yii::t('app', 'Рассказать о коррупционной схеме'), ['#']) ?>
                            <?= Html::a(Yii::t('app', 'Мне интересно знать откуда'), ['#']) ?>
                            <?= Html::a(Yii::t('app', 'Все обращения'), ['#']) ?>
                            <?= Html::a(Yii::t('app', 'Коррупционный рейтинг'), ['#']) ?>
                        </div>
                    </div>
                </div>

                <div class="bn-block-col">
                    <div class="bn-block type-2 margin2">
                        <div class="bb-title">
                            <h3 class="h3"> <?= Yii::t('app', 'Сводка коррупционных преступлений'); ?></h3>
                        </div>
                        <?php $victims = News::find()->where(['category_id' => 132])->orderBy(['date' => SORT_DESC])->limit(3)->all();
                        foreach ($victims as $victim) {
                            echo Html::beginTag('div', ['class' => 'general-post-cover']);
                            echo Html::a($victim->getThumb(), ['/news/view', 'id' => $victim->id], ['class' => 'general-side-block']);
                            echo Html::a($victim->title, ['/news/view', 'id' => $victim->id], ['class' => 'side-bar-title']);
                            echo Html::endTag('div');
                            echo Html::tag('div', '', ['class' => 'clear']);
                        }
                        ?>
                        <div class="more"><sup><?= Html::a('...', ['/news/category', 'id' => 132]) ?></sup></div>
                    </div>
                </div>

                <div class="bn-block-col">
                    <div class="bn-block type-2 margin2">
                        <div class="bb-title">
                            <h3 class="h3"> <?= Yii::t('app', 'Пресс релизы госорганов'); ?></h3>
                        </div>
                        <?php $victims = News::find()->where(['category_id' => 131])->orderBy(['date' => SORT_DESC])->limit(3)->all();
                        foreach ($victims as $victim) {
                            echo Html::beginTag('div', ['class' => 'general-post-cover']);
                            echo Html::a($victim->getThumb(), ['/news/view', 'id' => $victim->id], ['class' => 'general-side-block']);
                            echo Html::a($victim->title, ['/news/view', 'id' => $victim->id], ['class' => 'side-bar-title']);

                            echo Html::endTag('div');
                            echo Html::tag('div', '', ['class' => 'clear']);
                        }
                        ?>
                        <div class="more"><sup><?= Html::a('...', ['/news/category', 'id' => 131]) ?></sup></div>
                    </div>
                </div>

                <div class="bn-block-col">
                    <div class="bn-block type-2 margin2">
                        <div class="bb-title">
                            <h3 class="h3"> <?= Yii::t('app', 'Антикоррупционное образование'); ?></h3>
                        </div>
                        <?php $education = \frontend\models\Education::find()->orderBy(['date' => SORT_DESC])->limit(3)->all();
                        foreach ($education as $item) {
                            echo Html::beginTag('div', ['class' => 'general-post-cover']);
                            echo Html::a($item->getMainThumb(), ['/news/view', 'id' => $victim->id], ['class' => 'general-side-block']);
                            echo Html::a($item->title, ['/education/view', 'id' => $item->id], ['class' => 'side-bar-title']);
                            echo Html::endTag('div');
                            echo Html::tag('div', '', ['class' => 'clear']);
                        }
                        ?>
                        <div class="more"><sup><?= Html::a('...', ['/education/index']) ?></sup></div>
                    </div>
                </div>
            </div>

            <div class="central_block"><?= $content ?></div>
            <div class="right_bar">
                <?= Html::a(Html::img(\yii\helpers\Url::base() . "/images/site/banner.jpg")); ?>

                <div class="bn-block-col">
                    <div class="bn-block type-2 margin2">
                        <div class="bb-title">
                            <h3 class="h3"> <?= Yii::t('app', 'Достижения'); ?></h3>
                        </div>
                        <?php $victims = News::find()->where(['category_id' => 130])->orderBy(['date' => SORT_DESC])->limit(3)->all();
                        foreach ($victims as $victim) {
                            echo Html::beginTag('div', ['class' => 'general-post-cover']);
                            echo Html::a($victim->getThumb(), ['/news/view', 'id' => $victim->id], ['class' => 'general-side-block']);
                            echo Html::a($victim->title, ['/news/view', 'id' => $victim->id], ['class' => 'side-bar-title']);

                            echo Html::endTag('div');
                            echo Html::tag('div', '', ['class' => 'clear']);
                        }
                        ?>
                        <div class="more"><sup><?= Html::a('...', ['/news/category', 'id' => 130]) ?></sup></div>
                    </div>
                </div>
                <div class="sidebar_authority">
                    <div class="authority_heading">
                        Оцени гос.орган
                    </div>
                    <div class="demo">
                        <div class="item-1">
                            <ul id="authority-slider" class="authority-slider">
                                <?php
                                $authorities = Authority::find()->where('category_id!=0')->all();
                                foreach ($authorities as $authority) {
                                    echo Html::beginTag("li", ['class' => 'authority_li']);
                                    echo Html::beginTag("div", ['class' => 'sidebar_slider_cover']);
                                    echo Html::beginTag("div", ['class' => 'sidebar_slider_bg']);
                                    if ($authority->img) {
                                        echo Html::a(Html::img(Url::base() . "/images/authority/s_{$authority->img}"), ['/authority/view', 'id' => $authority->id]);
                                    } else {
                                        echo Html::a(Html::img(Url::base() . "/images/site/herb.png", ['style' => 'width:150px']), ['/authority/view', 'id' => $authority->id]);
                                    }
                                    echo Html::endTag("div");
                                    echo Html::endTag("div");
                                    echo Html::a($authority->title, ['/authority/view', 'id' => $authority->id], ['class' => 'sidebar_slider_title']);
                                    echo StarRating::widget([
                                        'name' => 'rating_2',
                                        'value' => $authority->getRating($authority->id),
                                        'pluginOptions' => [
                                            'showClear' => false,
                                            'showCaption' => false,
                                            'size' => 'xs',
                                            'stars' => 10,
                                            'min' => 0,
                                            'max' => 10,
                                            'step' => 1,
                                            'starCaptions' => [
                                                1 => 'Very Poor',
                                                2 => 'Poor',
                                                3 => 'Ok',
                                                4 => 'Good',
                                                5 => 'Very Good',
                                                6 => 'Very Good',
                                                7 => 'Very Good',
                                                8 => 'Very Good',
                                                9 => 'Very Good',
                                                10 => 'Very Good',
                                            ],
                                        ],

                                        'pluginEvents' => [
                                            "rating.change" => "function(event, value, caption) {
                                             $.ajax({
                                            url: \"/site/rating\",
                                            type: \"post\",
                                            data: {value:value,id:$authority->id}            
                                            });             
                                             }"],
                                    ]);
                                    echo Html::endTag("li");
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="bottom_heading">
                        <?= Html::a('Все органы', ['/authority/index']); ?>
                    </div>
                </div>
                <div class="bn-block-col">
                    <div class="bn-block type-2 margin2">
                        <div class="bb-title">
                            <h3 class="h3"> <?= Yii::t('app', 'Аналитика'); ?></h3>
                        </div>
                        <?php $analytics = Analytics::find()->where(['status' => 1])->orderBy(['date' => SORT_DESC])->limit(3)->all();
                        foreach ($analytics as $item) {
                            echo Html::beginTag('div', ['class' => 'general-post-cover']);
                            echo Html::a($item->title, ['/analytics/view', 'id' => $item->id], ['class' => 'side-bar-title analytics-title']);

                            echo Html::endTag('div');
                            echo Html::tag('div', '', ['class' => 'clear']);
                        }
                        ?>
                        <div class="more"><sup><?= Html::a('...', ['/analytics/index']) ?></sup></div>
                        <div class="add_opinion"><?= Html::a('Добавить мнение', ['/analytics/create']) ?></div>
                    </div>
                </div>

                <?php
                $script = <<<SCRIPT
            $(document).ready(function() {
                    $("#content-slider").lightSlider({
                        loop:true,
                        keyPress:true,
                        item:1,
                        slideMargin:0,
                        adaptiveHeight:true,
                       
                    });
                    
                     $("#authority-slider").lightSlider({
                        loop:true,
                        keyPress:true,
                        item:1,
                        slideMargin:0,
                        adaptiveHeight:true,
                        pager:false,
                       
                    });
                });
                
SCRIPT;
                $this->registerJs($script);
                ?>
            </div>

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
