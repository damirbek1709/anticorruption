<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
           'modelMap' => [
                'User' => 'frontend\models\User',
            ],
            'admins' => ['admin']
            // you will configure your module inside this file
            // or if need different configuration for frontend and backend you may
            // configure in needed configs
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@frontend/views/user'
                ],
            ],
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            //'suffix'=>'.html',
            'showScriptName' => false,
        ],
    ],
];
