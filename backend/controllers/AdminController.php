<?php


namespace backend\controllers;


use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout','signup'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],

            ],

            //Доступ только для админа
            [
                'class' => AccessControl::class,
                'only'  => ['index'],
                'rules' => [
                    [
                        'actions'       => ['index'],
                        'controllers'   => ['site', 'event', 'client','master'],
                        'allow'         => true,
                        'roles'         => ['admin', 'manager'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('perm_view-calendar');
                        },
                    ],
                    [
                        'actions'      => ['index'],
                        'controllers'  => ['site', 'event', 'client','master','todo'],
                        'allow'        => false,
                        'roles'        => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            Yii::$app->user->logout();
                            Yii::$app->session->setFlash('denied', Yii::$app->params['error']['access-is-denied']); ;
                           return $this->redirect('site/login') ;
//                            ;
                        }
                    ],
                ],
            ],

            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
}