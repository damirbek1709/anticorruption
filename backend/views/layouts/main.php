<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
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

    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container" style="padding: 10px;">
        <?php
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) {
            $logo = Yii::getAlias("@frontend/web/images/site/herb.png");
            $logo = Html::img(str_replace([Yii::getAlias('@frontend/web'), DIRECTORY_SEPARATOR], ['', '/'], $logo));

            NavBar::begin([
                'brandLabel' => $logo,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse',
                ],
            ]);
            $menuItems = [
                [
                    'label' => Yii::t('app', 'Госорганы'),
                    'url' => ['/authority/index']
                ],
                [
                    'label' => Yii::t('app', 'Обращения о коррупции'),
                    'items' => [
                        ['label' => Yii::t('app', 'Все'), 'url' => ['/report/index']],
                        ['label' => Yii::t('app', 'Обращения'), 'url' => ['/report/category', 'id' => 134]],
                        [
                            'label' => Yii::t('app', 'Коррупционные схемы'), 'url' => ['/report/category', 'id' => 137]
                        ],
                        [
                            'label' => Yii::t('app', 'Мне интересно знать, Откуда?'), 'url' => ['/report/category', 'id' => 138]
                        ],
                    ],
                ],
                [
                    'label' => Yii::t('app', 'Новости'),
                    'items' => [
                        ['label' => Yii::t('app', 'Новостная лента'), 'url' => ['/news/category', 'id' => 133]],
                        ['label' => Yii::t('app', 'Сводка коррупционных преступлений'), 'url' => ['/news/category', 'id' => 132]],
                        ['label' => Yii::t('app', 'Достижения'), 'url' => ['/news/category', 'id' => 130]],
                        ['label' => Yii::t('app', 'Пресс релизы госорганов'), 'url' => ['/news/category', 'id' => 131]],
                    ],
                ],

                [
                    'label' => Yii::t('app', 'Антикоррупционная политика'),
                    'items' => [
                        ['label' => Yii::t('app', 'Нормативно-правовые акты'), 'url' => ['/politics/category', 'id' => 150]],
                        ['label' => Yii::t('app', 'Международное сотрудничество'), 'url' => ['/politics/category', 'id' => 151]],

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
                    'label' => Yii::t('app', 'Отчеты'),
                    'url' => ['/document/index']
                ],

                [
                    'label' => Yii::t('app', 'Еще'),
                    'items' => [
                        [
                            'label' => Yii::t('app', 'Антикоррупционное образование'),
                            'url' => ['/education/index']
                        ],

                        [
                            'label' => Yii::t('app', 'Аналитика'),
                            'url' => ['/analytics/index']
                        ],


                        ['label' => Yii::t('app', 'Пользователи'), 'url' => ['/user/admin']],
                        [
                            'label' => Yii::t('app', 'Страницы'),
                            'url' => ['/page/index']
                        ],

                        [
                            'label' => Yii::t('app', 'Комментарии'),
                            'url' => ['/comments/index']
                        ],
                    ],

                ],
                [
                    'label' => Yii::t('app', 'Выход'),
                    'url' => ['/user/logout'],
                    'linkOptions' => ['data-method' => 'POST']
                ],
            ];

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left nav-backend'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        }
        /*echo Html::a('Обращения о коррупции', ['/report/index'], ['class' => 'admin_menu_item']);
        echo Html::a('Гос.Органы', ['/authority/index'], ['class' => 'admin_menu_item']);
        echo Html::a('Новости', ['/news/index'], ['class' => 'admin_menu_item']);
        echo Html::a('Антикоррупционное образование', ['/education/index'], ['class' => 'admin_menu_item']);
        echo Html::a('Аналитика', ['/analytics/index'], ['class' => 'admin_menu_item']);
        echo Html::a('Отчеты', ['/document/index'], ['class' => 'admin_menu_item']);*/
        ?>
    </div>
    <?php
    /*NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Обращение о коррупции', 'url' => ['/report/index']],
        ['label' => 'Гос.Органы', 'url' => ['/authority/index']],
        ['label' => 'Новости', 'url' => ['/news/index']],
    ];
    $menuItems[] = [
        'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
        'url' => ['/user/security/logout'],
        //'linkOptions' => ['data-method' => 'post']
    ];

    /*echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end()*/;
    ?>

    <div class="container">
        <?php
        /*echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]);*/
        ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
