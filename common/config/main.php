<?php
return [
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'language'   => 'ru-RU',
    'timeZone'   => 'UTC',
    'modules'    => [
        'profile' => [
            'class' => 'common\modules\profile\module',
        ],
        'client'  => [
            'class' => 'common\modules\client\Module',
        ],
        'blog' => [
            'class' => 'common\modules\blog\Blog',
        ],
        'calendar' => [
            'class' => 'common\modules\calendar\Module',
        ],
    ],
    'components' => [
        'cache'       => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'storage'     => [
            'class' => 'common\components\Storage'
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => false,
            ],
        ],

        /*'errorHandler' => [
            'errorAction' => 'site/error',
        ],*/
    ],
];
