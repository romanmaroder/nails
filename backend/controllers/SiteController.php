<?php

namespace backend\controllers;

use common\models\Event;
use common\models\User;
use backend\models\SignupForm;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Site controller
 */
class SiteController extends AdminController
{

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

        $countEvents = Event::find()->select('COUNT(client_id)')->where(['master_id'=>$masterIds])->groupBy('master_id')
            ->asArray()
            ->all();


        return $this->render('index',[
            'count'=>$countEvents
        ]);
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
