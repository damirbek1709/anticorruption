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
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container" style="padding: 10px;">
        <div class="admin_bar">
            <?php
            echo Html::a('Обращение о коррупции', ['/report/index'], ['class' => 'admin_menu_item']);
            echo Html::a('Гос.Органы', ['/authority/index'], ['class' => 'admin_menu_item']);
            echo Html::a('Новости', ['/news/index'], ['class' => 'admin_menu_item']);
            echo Html::a('Антикоррупционное образование', ['/education/index'], ['class' => 'admin_menu_item']);
            echo Html::a('Аналитика', ['/analytics/index'], ['class' => 'admin_menu_item']);
            echo Html::a('Отчеты', ['/document/index'], ['class' => 'admin_menu_item']);
            ?>
        </div>
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
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
