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
use yii\bootstrap\Modal;
use frontend\models\Report;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php echo Url::base(); ?>/images/site/favicon.ico" type="image/x-icon"/>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= Url::base() ?>/images/site/favicon-16x16.png">
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
            <div class="left_top_header col-md-5 pad-remove">
                <?= Html::a(Yii::t('app', 'О проекте'), ['/site/about']) ?>
                <? //= Html::a(Yii::t('app', 'Контакты'), ['/site/contact']) ?>
                <? //= Html::a(Yii::t('app', 'Обратная связь'), ['/site/feedback']) ?>
                <?php
                if (Yii::$app->user->isGuest) {
                    echo Html::a(Yii::t('app', 'Вход на сайт'), ['/user/login']);
                    echo Html::a(Yii::t('app', 'Регистрация'), ['/user/register']);
                } else {
                    echo Html::a(Yii::t('app', 'Выход'), ['/user/logout'], ['data-method' => 'post']);
                }

                $languageArr = [
                    'ky' => Html::a('<span class="ky">Кыргызча</span>', '?language=ky'),
                    'ru' => Html::a('<span class="ru">Русский</span>', '?language=ru'),
                    //'en' => Html::a('<span class="rus">English</span>', '?language=en')
                ] ?>

                <?
                foreach ($languageArr as $key => $val) {
                    echo $val;
                }
                ?>
            </div>


            <div class="right_top_header col-sm-6 pad-remove">
                <div class="col-md-3 pad-remove" style="float:right">
                    <?= Html::a(Html::tag('span', ''), 'https://itunes.apple.com/us/app/anticorruption.kg/id1315555330?mt=8', ['class' => 'apple_icon']); ?>
                    <?= Html::a(Html::tag('span', ''), 'https://play.google.com/store/apps/details?id=kg.prosoft.anticorruption', ['class' => 'android_icon']); ?>
                    <?= Html::a(Html::tag('span', ''), 'http://facebook.com', ['class' => 'fb_icon']); ?>
                </div>
                <div class="col-md-9 pad-remove" style="overflow: hidden">
                    <?php echo Html::beginForm(['site/search'], 'post', ['class' => 'form-inline']);
                    echo Html::beginTag('div', ['style' => 'float:left;width:100%;overflow:hidden']);
                    echo Html::beginTag('div', ['class' => 'input-group', 'style' => 'width:100%']);
                    echo Html::input('text', 'search', '', ['class' => 'form-control', 'placeholder' => Yii::t('app', 'Поиска по сайту')]);
                    echo Html::beginTag('div', ['class' => 'input-group-addon']);
                    echo Html::tag('button', '<i class="glyphicon glyphicon-search"></i>', ['type' => 'submit', 'class' => 'btn-search']);
                    echo Html::endTag('div');
                    echo Html::endTag('div');
                    echo Html::endTag('div');
                    echo Html::endForm();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="main_header report_label_transform">
        <div class="centralizer">
            <?= Html::beginTag('a', ['href' => '/site/index']) ?>
            <div class="logo"></div>
            <span class="site-name"><?= Yii::t('app', 'Антикоррупционный портал<br> Кыргызской Республики'); ?></span>
            <?= Html::endTag('a'); ?>

            <div class="report_header">
                <?php
                $separator = ' ';
                //$report_count = Report::find()->where(['status' => 1])->count();
                $report_count = Report::find()->count();
                $formated_num = preg_replace('/(?<=\d)\x' . bin2hex($separator[0]) . '(?=\d)/',
                    $separator,
                    number_format($report_count, 0, '.', $separator));
                ?>
                <?
                echo Html::beginTag('div', ['class' => 'report_number']);
                echo Html::a(Yii::t('app', 'Сообщений о коррупции:') . ' ' . $formated_num, ['/report/index'], ['style' => 'color:#fff;']);
                echo Html::endTag('div'); ?>
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
            'brandLabel' => '<div class="back_transform"><div class="logo"></div>
            <span class="site-name">Антикоррупционный портал<br> Кыргызской Республики</span></div>',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse',
            ],
        ]);
        $menuItems = [
            /*['label' => '<span class="home-link"></span>', 'url' => ['/site/index'],
                'linkOptions' => [
                    'style' => 'padding:13px 0'
                ],
            ],*/
            /*['label' => 'Главная', 'url' => ['/site/index'],
                'linkOptions' => [
                    'style' => 'padding-left:12px'
                ],
            ],*/
            [
                'label' => Yii::t('app', 'Новости'),
                'linkOptions' => [
                    'style' => 'padding-left:12px'
                ],
                'items' => [
                    ['label' => Yii::t('app', 'Новостная лента'), 'url' => ['/news/category/133']],
                    ['label' => Yii::t('app', 'Сводка коррупционных преступлений'), 'url' => ['/news/category/132']],
                    ['label' => Yii::t('app', 'Достижения'), 'url' => ['/news/category/130']],
                    ['label' => Yii::t('app', 'Пресс-релизы госорганов'), 'url' => ['/news/category/131']],
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
                'label' => Yii::t('app', 'Предупреждение коррупции'),
                'items' => [
                    ['label' => Yii::t('app', 'Антикоррупционнные планы и отчеты'), 'url' => ['/document/category', 'id' => 147]],
                    ['label' => Yii::t('app', 'Уполномоченные по вопросам противодействия коррупции'), 'url' => ['/page/view', 'id' => 3]],
                    ['label' => Yii::t('app', 'Общественные советы'), 'url' => ['/page/view', 'id' => 2]],
                    ['label' => Yii::t('app', 'Исследования'), 'url' => ['/document/category', 'id' => 146]],
                    ///['label' => Yii::t('app', 'Декларация о доходах'), 'url' => ['/document/category', 'id' => 149]],
                ],
            ],
            [
                'label' => Yii::t('app', 'Пресечение коррупции'),
                'items' => [
                    ['label' => Yii::t('app', 'Информация правоохранительных органов'), 'url' => ['/resistance/category', 'id' => 152]],
                    ['label' => Yii::t('app', 'Информация органов прокуратуры'), 'url' => ['/resistance/category', 'id' => 153]],

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
        <div class="width_limiter col-md-12 pad-remove">
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
                        <?= Html::tag("div", Html::a(Yii::t('app', 'Пресс-релизы госорганов'), ['/news/category', 'id' => 131], ['class' => 'rmb']), ["class" => "left_category_list"]); ?>
                    </div>
                </div>
                <div class="l_report_block">
                    <?= Html::a(Yii::t('app', 'Исследования'), ['/document/category', 'id' => 146], ['class' => 'report_link']); ?>
                    <?= Html::a(Yii::t('app', 'Антикоррупционные планы и отчеты'), ['/document/category', 'id' => 147], ['class' => 'report_link']); ?>
                    <?= Html::a(Yii::t('app', 'Международное сотрудничество'), ['/politics/category', 'id' => 151], ['class' => 'report_link']); ?>
                </div>

                <div class="l_corruption_block">
                    <div class="padder">
                        <div class="italic_header">
                            <?= Yii::t('app', 'Пресечение коррупции'); ?>
                        </div>
                        <div class="l_corruption_links">
                            <?= Html::a(Yii::t('app', 'Информация правохоохранительных органов'), ['/resistance/category', 'id' => 152]); ?>
                            <?= Html::a(Yii::t('app', 'Информация органов прокуратуры'), ['/resistance/category', 'id' => 153]); ?>
                        </div>
                        <div class="italic_header top_marginer">
                            <?= Yii::t('app', 'Карта коррупции'); ?>
                        </div>
                        <div class="l_corruption_links">
                            <?= Html::a(Yii::t('app', 'Сообщить о коррупции'), ['/report/create']) ?>
                            <?= Html::a(Yii::t('app', 'Рассказать о коррупционной схеме'), ['/report/create'],
                                [
                                    'data-method' => 'POST',
                                    'data-params' => [
                                        'paramType' => 137,
                                    ]
                                ]); ?>
                            <?= Html::a(Yii::t('app', 'Мне интересно знать откуда'), ['report/create'],
                                [
                                    'data-method' => 'POST',
                                    'data-params' => [
                                        'paramType' => 138,
                                    ]
                                ]
                            ); ?>
                            <?= Html::a(Yii::t('app', 'Все обращения'), ['/report/index']) ?>
                            <?= Html::a(Yii::t('app', 'Коррупционный рейтинг'), ['/authority/index']) ?>
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
                            if ($item->getMainThumb()) {
                                echo Html::a($item->getMainThumb(), ['/news/view', 'id' => $victim->id], ['class' => 'general-side-block']);
                            }
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
                <?= Html::a(Html::img(\yii\helpers\Url::base() . "/images/site/banner.jpg"), ['/site/map']); ?>

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
                    <?php
                    Modal::begin([
                        'header' => '<h2>Пожалуйста, авторизуйтесь</h2>',
                        'headerOptions' => ['id' => 'modalHeader'],
                        'id' => 'modal',
                        'size' => 'modal-md',
                        //keeps from closing modal with esc key or by clicking out of the modal.
                        // user must click cancel or X to close
                        'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
                    ]); ?>

                    <div id="modalContent">
                        <div class="main-heading">
                            <div style="font-size: 15px">
                                Для того чтобы проголосовать вам
                                необходимо <?php echo Html::a('авторизоваться', ['/user/login'], ['style' => 'color:#d80403']); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    Modal::end();
                    ?>
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
                                        echo Html::a(Html::img(Url::base() . "/images/authority/s_{$authority->id}_{$authority->img}"), ['/authority/view', 'id' => $authority->id]);
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
                                            data: {value:value,id:$authority->id},
                                            success: function(response){
                                                if(response==false)
                                                {                                                
                                                    $('#modal').modal('show')
                                                }
                                            }           
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

                <div class="bn-block-col">
                    <div class="bn-block type-2 margin2" style="width: 100%;display: inline-block">
                        <div class="bb-title">
                            <h3 class="h3"> <?= Yii::t('app', 'Подписаться на рассылку'); ?></h3>
                        </div>
                        <?php
                        $email = new \frontend\models\Subscriber();
                        $form = ActiveForm::begin([
                            'id' => 'subscribe-form',
                            'action' => '/site/subscribe',
                            'enableAjaxValidation' => true,
                            //'validationUrl' => '/site/validate-email',
                        ]); ?>

                        <?= $form->field($email, 'email')->textInput(['maxlength' => true,
                            'placeholder' => 'E-mail',
                            'class' => 'form-control sharper'])->label(false);
                        ?>

                        <div class="form-group">
                            <?= Html::submitButton(Yii::t('app', 'Отправить'), ['class' => 'subs btn btn-primary']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
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
        <div class="pull-left report_label_transform">
            <?= Html::a(Html::tag('div', ''), 'http://un.org.kg', ['class' => 'un_link']); ?>
        </div>

        <div class="pull-center">© <?= date('Y'); ?> Антикоррупционный Портал <br> Кыргызской Республики</div>

        <div class="pull-right">
            <?= Html::a(Html::tag('div', ''), 'https://play.google.com/store/apps/details?id=kg.prosoft.anticorruption', ['class' => 'android_footer_icon']); ?>
            <?= Html::a(Html::tag('div', ''), 'https://itunes.apple.com/us/app/anticorruption.kg/id1315555330?mt=8', ['class' => 'apple_footer_icon']); ?>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
