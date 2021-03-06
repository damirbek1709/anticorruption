<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name'=>'Anticorruption.kg',
    'basePath' => dirname(__DIR__),
    'defaultRoute' => 'report/index',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language'=>'ru-RU',
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableFlashMessages' => false,
            'enableRegistration' => false,
            'enableUnconfirmedLogin' => false,
            'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['admin','damirbek@gmail.com'],
            'controllerMap' => [
                //'admin' => 'backend\controllers\user\AdminController',
                //'security' => 'frontend\controllers\user\SecurityController'
            ],
        ],
        // following line will restrict access to profile, recovery, registration and settings controllers from backend
        //'as backend' => 'dektrium\user\filters\BackendFilter',
        //'controllers' => ['profile','admin', 'recovery', 'registration', 'settings']

    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'baseUrl' => '/admin',
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'OJp79x4y2u4D017tKrSnNEcFwKOt__I6',
            'csrfParam' => '_backendCSRF',
            'csrfCookie' => [
                'httpOnly' => true,
                'path' => '/admin',
            ],
        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@backend/views/user'
                ],
            ],
        ],

        // Configuration Session Backend [Yii2-User] //
        'session' => [
            'name' => 'BACKENDSESSID',
            'cookieParams' => [
                'httpOnly' => true,
                'path' => '/admin',
            ],
        ],

        'user' => [
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_backendIdentity',
                'path' => '/admin',
                'httpOnly' => true,
            ],
        ],

        /*'session' => [
            'name' => 'BACKENDSESSID',
            'cookieParams' => [
                'httpOnly' => true,
                'path'     => '/',
            ],
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

        'urlManagerFrontend' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/',
        ],

    ],
    'params' => $params,
];
