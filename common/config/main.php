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
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'elitkacrm@gmail.com',
                'password' => 'elitkacrm85qw',
                'port' => '587',
                'encryption' => 'tls'
                /*'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'lussovillage@yandex.ru',
                'password' => 'lussovillage2017qw',
                'port' => '465',
                'encryption' => 'SSL',*/
            ],

        ],
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
