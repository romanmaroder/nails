<?php

namespace backend\controllers;

use common\models\Event;
use common\models\Photo;
use Yii;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends AdminController
{
    public $enableCsrfValidation = false;
    /**
     * {@inheritdoc}
     */
    /*public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }*/

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $masterIds = Yii::$app->authManager->getUserIdsByRole('master');

        $event       = new Event();
        $countEvents = $event->getTotalEventsMaster($masterIds);

        $photo            = new Photo();
        $portfolioCount   = $photo->getTotalPortfolioPhotoCount($masterIds);
        $workCount        = $photo->getTotalMasterPhotoCount($masterIds);
        $clientPhotoCount = $photo->getTotalPhotoCount($masterIds);




        return $this->render(
            'index',
            [
                'count'            => $countEvents,
                'portfolioCount'   => $portfolioCount,
                'workCount'        => $workCount,
                'clientPhotoCount' => $clientPhotoCount,
            ]
        );
    }

    /**
     * Login action.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

//        $this->layout = 'blank';
        $this->layout = 'main-login';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render(
                'login',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * Logout action.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionError()
    {
        if ($error = Yii::$app->errorHandler->error) {
            $this->render('error', $error);
        }
    }
}
