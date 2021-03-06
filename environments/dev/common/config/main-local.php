<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=host1827487',
            'username' => 'host1827487',
            'password' => 'N8c4ZkRzGj',
            'charset' => 'utf8',
        ],
        /*'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=nails',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],*/
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
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'roma12041985@yandex.ru',
                'password' => 'Roman_maroder',
                'port' => '587', // 465
                'encryption' => 'tls', // tls
                'streamOptions' => [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false
                    ]
                ]
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => false,
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'smsSender' => [
            'class' => 'common\components\sms\SmsSender'
        ],
        'storage' => [
            'class' => 'common\components\Storage'
        ],
    ],
    'modules' => [
        'profile' => [
            'class' => 'common\modules\profile\module',
        ],
        'client' => [
            'class' => 'common\modules\client\Module',
        ],
        'blog' => [
            'class' => 'common\modules\blog\Blog',
        ],
        'calendar' => [
            'class' => 'common\modules\calendar\Module',
        ],
    ],
];
