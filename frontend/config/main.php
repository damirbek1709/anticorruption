<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'language'=>'ru',
    'sourceLanguage'=>'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        // Configuration Yii2-User Frontend //
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableFlashMessages' => true,
            'enableRegistration' => true,
            'enableUnconfirmedLogin' => true,
            'controllerMap' => [
                'admin' => 'app\controllers\user\AdminController'
            ],
            //'confirmWithin' => 21600,
            //'cost' => 12,
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl'=>''
        ],

        'user' => [
            'identityCookie' => [
                'name'     => '_frontendIdentity',
                'path'     => '/',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            'name' => 'FRONTENDSESSID',
            'cookieParams' => [
                'httpOnly' => true,
                'path'     => '/',
            ],
        ],

        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'timeFormat' => 'H:i',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'class' => 'yii\i18n\Formatter',
        ],

        /*'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'contact'=>'site/contact',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => ['jquery.min.js']
                ],
                /*
                'yii\web\YiiAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => ['js/yii.min.js']
                ],
                */
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'css' => ['css/bootstrap.min.css'],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => ['js/bootstrap.min.js'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
