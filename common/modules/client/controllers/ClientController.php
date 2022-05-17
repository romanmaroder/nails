<?php

namespace common\modules\client\controllers;

use Yii;
use common\models\User;
use common\models\Profile;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ClientController implements the CRUD actions for User model.
 */
class ClientController extends Controller
{
    /**
     * При создании клиента через админ-панель
     * Пароль и почта пользователя по-умолчанию
     */
    protected const DEFAULT_PASSWORD = '11111111';
    protected const DEFAULT_EMAIL = '@user.com';

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST','GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                //'only' => ['login', 'logout', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * Lists all User models.
     * Все записи кроме 1 (Администратора)
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = User::getDataProvider();


        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Displays a single User model.
     *
     * @param int $id
     *
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new User();
        $profile = new Profile();

        if ($model->load(Yii::$app->request->post())) {


            $model->email = 'user' . rand(1, 5000) . self::DEFAULT_EMAIL;

            $model->setPassword(self::DEFAULT_PASSWORD);
            $model->generateAuthKey();
            $model->generateEmailVerificationToken();

            if ($model->save()) {

                if ($model->roles){
                    $profile->user_id = $model->id;
                    $profile->color = $model->color;
                    $profile->save();
                }
                $model->saveRoles();

                Yii::$app->session->setFlash('info', 'Клиент <b>'.$model->username . '</b> сохранен. ');

                return $this->redirect('client/index');
            }
            Yii::$app->session->setFlash('danger', 'Сохраните клиента еще раз. ');
        }

        return $this->render(
            'create',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        /*echo '<pre>';
        var_dump($model);
        echo '</pre>';
        die();*/
        $profile= Profile::find()->where(['user_id'=>$id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(!empty($profile)){
                $profile->color = $model->color;
                $profile->save();
            }elseif ($model->roles){
                    $profile = new Profile();
                    $profile->user_id = $model->id;
                    $profile->color = $model->color;
                    //$model->saveRoles();
                    $profile->save();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render(
            'update',
            [
                'model' => $model,

            ]
        );
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        Yii::$app->authManager->revokeAll($id);

        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): User
    {

        if ( ( $model = User::find()->with('profile')->where(['id'=>$id])->one() ) !== null) {
            return $model;
        }


        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}
