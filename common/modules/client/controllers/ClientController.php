<?php

namespace common\modules\client\controllers;

use backend\models\UserBackend;
use Yii;
use common\models\Profile;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
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

                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['create','update','view','delete','master'],
                        'roles'   => ['admin', 'manager']

                    ],
                    [
                        'allow'   => true,
                        'actions' => ['login'],
                        'roles'   => ['?'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view'],
                        'roles'   => ['master'],
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
     *  @throws InvalidConfigException
     */
    public function actionIndex(): string
    {
        $dataProvider = UserBackend::getDataProvider();

        $inactive = UserBackend::inactiveUser();

        if (!empty($inactive) && Yii::$app->user->can('admin')) {

            $message = '';
            foreach ($inactive as $user) {

                $message .= "{$user['username']}</br>";
            }
            Yii::$app->session->setFlash('danger', "{$message}");
        }


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
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $model   = new UserBackend();
        $profile = new Profile();

        if ($model->load(Yii::$app->request->post())) {
            $model->generateEmail();
            $model->setPassword($model->defaultPassword());
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
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        #$profile= Profile::find()->where(['user_id'=>$id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!empty($model->profile->user_id)) {
                $model->profile->color = $model->color;
                $model->profile->save();
            } elseif ($model->roles) {
                $profile          = new Profile();
                $profile->user_id = $model->id;
                $profile->color   = $model->color;

                $profile->save();
            }
            if ($model->roles === '' && $model->profile->color) {
                $profile = Profile::getUserProfileInfo($model->profile->user_id);
                $profile->delete();
            }

            $model->saveRoles();
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
     * @return Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $this->on($this::EVENT_AFTER_ACTION,[$this->findModel($id),'saveRoles']);

        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionMaster()
    {
        $masterIds = Yii::$app->authManager->getUserIdsByRole('master');

        /*$query = User::find()->select('user.*')
            ->leftJoin('auth_assignment', '`auth_assignment`.`user_id` = `user`.`id`')
            ->andWhere(['auth_assignment.item_name'=>['master','manager']]);*/

        $query = UserBackend::find()->where(['id' => $masterIds])->with(['profile']);

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );

        return $this->render(
            'master', [
                        'dataProvider' => $dataProvider,
                    ]
        );
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     *
     * @return array|ActiveRecord|null
     * @throws NotFoundHttpException
     */
    protected function findModel(int $id)
    {

        if ( ( $model = UserBackend::find()->with('profile')->where(['id'=>$id])->one() ) !== null) {
            return $model;
        }


        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}
