<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-backend',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'           => ['log'],
    'modules'             => [
        'telegram' => [
            'class' => 'backend\modules\telegram\Module',
        ],
        'viber'    => [
            'class' => 'backend\modules\viber\Module',
        ],

    ],
    'name'                => 'NAILS-ADMIN',
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
                ''                             => 'site/index',
                //'login'           => 'site/login',
                'event'                        => '/calendar/event/index',
                'archive'                      => '/archive/index',
                'statistic'                    => '/calendar/event/statistic',
                'save'                         => '/calendar/event/save',
                'expenses'                     => '/expenses/',
                'expenses/new'                 => '/expenses/create',
                'expenses/update/<id:\d+>'     => '/expenses/update',
                'expenses/<id:\d+>'            => '/expenses/view',
                'rate'                         => '/service-user/index',
                'rate/new'                     => '/service-user/create',
                'rate/update/<id:\d+>'         => '/service-user/update',
                'rate/<id:\d+>'                => '/service-user/view',
                'expenseslist'                 => '/expenseslist/',
                'expenseslist/new'             => '/expenseslist/create',
                'expenseslist/update/<id:\d+>' => '/expenseslist/update',
                'expenseslist/<id:\d+>'        => '/expenseslist/view',
                'client'                       => 'client/client/index',
                'client/master'                => 'client/client/master',
                'client/new'                   => '/client/client/create',
                'client/update/<id:\d+>'       => '/client/client/update',
                'client/<id:\d+>'              => '/client/client/view',
                'client/delete/<id:\d+>'       => '/client/client/delete',
                'category'                     => 'category/index',
                'category/new'                 => 'category/create',
                'service'                      => 'service/index',
                'service/new'                  => 'service/create',
                'service/<id:\d+>'             => '/service/view',
                'post'                         => '/blog/post/index',
                'post/new'                     => '/blog/post/create',
                'post/<id:\d+>'                => '/blog/post/view',
                'todo'                         => '/todo/todo/index',
                'account'                      => '/profile/account/index',
                '<action:\w+ >'                => 'site/<action>',
                [
                    'pattern' => '<action:about|login>',
                    'route'   => 'site/<action>',
                    //'suffix' => '.html',
                ],

            ],
        ],
    ],
    'params'              => $params,
];