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
            'identityCookie'  => [
                'name'          => '_identity-backend',
                'httpOnly'      => true,
            ],
            /*'on afterLogin' => function (\yii\web\UserEvent $event) {
                $user = $event->identity;
                Yii::$app->session->setFlash('info', Yii::$app->smsSender->checkTimeOfDay() . $user->username .'!');

            }*/

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
                ''                                                        => 'site/index',
                'event'                                                   => 'calendar/event/index',
                'statistic'                                               => 'calendar/event/statistic',
                'save'                                                    => 'calendar/event/save',
                'expenses'                                                => 'expenses/index',
                'expenses/<id:\d+>'                                       => 'expenses/view',
                'expenses/new'                                            => 'expenses/create',
                'rate'                                                    => 'service-user/index',
                'rate/<id:\d+>'                                           => 'service-user/view',
                'rate/new'                                                => 'service-user/create',
                'expenseslist'                                            => 'expenseslist/index',
                'expenseslist/<id:\d+>'                                   => 'expenseslist/view',
                'expenseslist/new'                                        => 'expenseslist/create',
                'client'                                                  => 'client/client/index',
                'client/<id:\d+>'                                         => 'client/client/view',
                'client/new'                                              => 'client/client/create',
                'master'                                                  => 'client/client/master',
                'category'                                                => 'category/index',
                'category/<id:\d+>'                                       => 'category/view',
                'category/new'                                            => 'category/create',
                'service'                                                 => 'service/index',
                'service/<id:\d+>'                                        => 'service/view',
                'service/new'                                             => 'service/create',
                'post'                                                    => 'blog/post/index',
                'post/<id:\d+>'                                           => 'blog/post/view',
                'post/new'                                                => 'blog/post/create',
                'todo'                                                    => 'todo/todo/index',
                'account'                                                 => 'profile/account/index',
                'account/<id:\d+>'                                        => 'profile/account/view',
                '<module>/<controller>/<action:(update|delete)>/<id:\d+>' => '<module>/<controller>/<action>',
                '<controller>/<action:(update|delete)>/<id:\d+>'          => '<controller>/<action>',
                '<action:\w+ >'                                           => 'site/<action>',
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