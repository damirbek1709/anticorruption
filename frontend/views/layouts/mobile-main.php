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

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700&amp;subset=cyrillic,cyrillic-ext"
              rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext"
              rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,700&amp;subset=cyrillic" rel="stylesheet">
        <link rel="stylesheet" href="<?= Url::base() ?>/css/mobile.css"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

    </head>
    <body>
    <?php $this->beginBody() ?>


    <div class="wrap">
        <div class="mobile_cover">
            <?php
            NavBar::begin([
                'brandLabel' => '<span class="herb_logo"></span>Антикоррупционный портал<br> Кыргызской Республики',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
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
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Регистрация', 'url' => ['/user/register']];
                $menuItems[] = ['label' => 'Войти', 'url' => ['/user/login']];
            } else {
                $menuItems[] = '<li>'
                    . Html::beginForm(['/user/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>';
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'items' => $menuItems,
            ]);
            NavBar::end();
            ?>
        </div>

        <div class="container fixed-marginer">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            © <?= date('Y') ?>
            <div class="clear"></div>
            <div style="width: 70%;margin: 0 auto">
                <?=Yii::t('app','Антикоррупционный портал Кыргызской Республики');?>
            </div>
            <div class="clear"></div>
            <div class="mobile_social_icons">
                <?=Html::a('<span class=mobile_fb></span>','http://www.facebook.com');?>
                <?=Html::a('<span class=mobile_android></span>','http://www.play.google. com');?>
                <?=Html::a('<span class=mobile_apple></span>','http://www.appstore.com');?>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>