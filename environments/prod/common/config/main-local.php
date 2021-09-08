<?php
return [
    'language'   => 'ru-RU',
    'timeZone'   => 'UTC',
    'modules'    => [
        'profile' => [
            'class' => 'common\modules\profile\module',
        ],
        'client'  => [
            'class' => 'common\modules\client\Module',
        ],
        'calendar' => [
            'class' => 'common\modules\calendar\Module',
        ],
        'blog' => [
            'class' => 'common\modules\blog\Blog',
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=nails',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,

            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport'        => [
                'class'         => 'Swift_SmtpTransport',
                'host'          => 'smtp.yandex.ru',
                'username'      => 'roma12041985@yandex.ru',
                'password'      => 'Roman_maroder',
                'port'          => '587', // 465
                'encryption'    => 'tls', // tls
                'streamOptions' => [
                    'ssl' => [
                        'verify_peer'      => false,
                        'verify_peer_name' => false
                    ]
                ]
            ],
        ],
        'cache'       => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache' // Храним кэш в common/runtime/cache
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'storage'=>[
            'class'=>'common\components\Storage'
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => true,
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => false,
            ],
        ],
    ],
];
