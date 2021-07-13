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
    ],
    'components' => [
        'cache'       => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'storage'=>[
            'class'=>'common\components\Storage'
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
];
