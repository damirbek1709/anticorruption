<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php')
);
return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'baseUrl' => '/api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => false,
            'enableSession'=>false,
            'loginUrl'=>null
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-api',
        ],
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
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'category',
                    'extraPatterns' => [
                        'GET depend' => 'depend',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'page'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'report'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'comments'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'news'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'authority'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'vocabulary'],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'lookup',
                    'extraPatterns' => [
                        'GET depend' => 'depend',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user',
                    'extraPatterns' => [
                        'POST login' => 'login',
                    ],
                ],
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ]
    ],
    'params' => $params,
];
