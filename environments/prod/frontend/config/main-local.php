<?php

return [
    'id' => 'app-frontend',
    'controllerNamespace' => 'frontend\controllers',
    'name' => 'NAILS',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'event' => '/calendar/event/index',
                'client' => '/client/client/index',
                'client/<id:\d+>' => '/client/client/view',
                'portfolio' => '/site/portfolio',
                'about' => '/site/about',
                'login' => '/site/login',
                'account' => '/profile/account',
                'master/<id:\d+>' => '/site/view',
                'post/<id:\d+>' => '/blog/post/post',
                'post/<slug>' => '/blog/post/post',
            ],
        ],
    ],
];
