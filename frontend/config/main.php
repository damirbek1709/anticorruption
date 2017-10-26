<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'language' => 'ru-RU',
    'sourceLanguage' => 'ru-RU',
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
        'authClientCollection' => [
            'class' => \yii\authclient\Collection::className(),
            'clients' => [
                'facebook' => [
                    'class' => 'dektrium\user\clients\Facebook',
                    'clientId' => '2026864364212005',
                    'clientSecret' => '29f4870c9076767061dd83b6aa515241',
                ],
                'twitter' => [
                    'class' => 'dektrium\user\clients\Twitter',
                    'consumerKey' => 'AIw8j1pjMcH2QXUAPLaQQJ3SL',
                    'consumerSecret' => '72HJCEZjjNU77TgrELQoskYpxhSuQslXciMolHrF9AW4lfnwbt',
                ],

                'google' => [
                    'class' => 'dektrium\user\clients\Google',
                    'clientId' => '170973181296-95l2bfoimt60cgoe7nairr0i0vt7kko7.apps.googleusercontent.com',
                    'clientSecret' => '6RLc8LGSfLtm7NjXoPn8J8ad',
                ],
                'odnoklassniki' => [
                    'class' => 'kotchuprik\authclient\Odnoklassniki',
                    'applicationKey' => 'CBAJOMOLEBABABABA',
                    'clientId' => '1256974336',
                    'clientSecret' => 'E149483E8F467EFDBFDDD4F7',
                ],

                // here is the list of clients you want to use
                // you can read more in the "Available clients" section
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => ''
        ],

        'user' => [
            'identityCookie' => [
                'name' => '_frontendIdentity',
                'path' => '/',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            'name' => 'FRONTENDSESSID',
            'cookieParams' => [
                'httpOnly' => true,
                'path' => '/',
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
                'contact' => 'site/contact',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'urlManagerFrontend' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/',
        ],

        'assetManager' => [
            'appendTimestamp' => true,
        ],
    ],
    'params' => $params,
];
