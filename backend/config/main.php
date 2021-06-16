<?php

use yii\helpers\Url;

$params = array_merge(
    require __DIR__.'/../../common/config/params.php',
    require __DIR__.'/../../common/config/params-local.php',
    require __DIR__.'/params.php',
    require __DIR__.'/params-local.php'
);

return [
    'id'                  => 'app-backend',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'           => ['log'],
    'modules'             => [
        'employees' => [
            'class' => 'backend\modules\employees\Module',
        ],
    ],
    'name'                => 'NAILS-ADMIN',
    'timeZone'            => 'UTC',
    'language'            => 'ru-RU',
    'components'          => [
        'request'      => [
            'csrfParam' => '_csrf-backend',
            'baseUrl'   => '/admin',
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-backend', 'httpOnly' => true],

        ],
        'session'      => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                ''       => 'site/index',
                'login'  => 'site/login',
                'event'  => 'event/index',
                'master' => 'employees/master',
                'client' => 'client/client'
            ],
        ],
    ],
    'params'              => $params,
];
