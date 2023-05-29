<?php
return [
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'language'   => 'ru',
    'timeZone'   => 'Europe/Moscow',
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
        'todo' => [
            'class' => 'common\modules\todo\Module',
        ],
    ],
    'components' => [
        'formatter' => [
            'locale' => 'ru-RU',
            'defaultTimeZone' => 'Europe/Moscow',
            'timeZone' => 'Europe/Moscow',
            'dateFormat' => 'dd.MM.yyyy',
            'timeFormat' => 'HH:mm:ss',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm:ss',
        ],
        'cache'       => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@common/runtime/cache' // Храним кэш в common/runtime/cache
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'storage'     => [
            'class' => 'common\components\Storage'
        ],
        'smsSender'=>[
            'class'=>'common\components\sms\SmsSender'
        ],
        'seo' => [
            'class' => 'common\components\seo\SeoComponent',
            ],
        'assetManager' => [
            'appendTimestamp' => true,
            #'linkAssets' => true,
            'bundles' => [
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
            ],
        ],

        /*'errorHandler' => [
            'errorAction' => 'site/error',
        ],*/
    ],
];
