<?php

$params = array_merge(
    require __DIR__.'/../../common/config/params.php',
    require __DIR__.'/../../common/config/params-local.php',
    require __DIR__.'/params.php',
    require __DIR__.'/params-local.php'
);

return [
    'id'                  => 'app-frontend',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'name'                => 'NAILS',
    'language'            => 'ru',
    'components'          => [
        'request'      => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl'   => '',
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session'      => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
                ''              => 'site/index',
                'event'         => '/calendar/event/index',
                'client'         => '/client/client/index',
                'client/<id:\d+>'         => '/client/client/view',
                'portfolio'         => '/site/portfolio',
                'about'         => '/site/about',
                'login'         => '/site/login',
                'account'         => '/profile/account',
                'post/<id:\d+>' => '/blog/post/post',
                'post/<slug>'   => '/blog/post/post',
            ],
        ],
        'assetManager' => [
        ],
    ],
    'params'              => $params,
];
