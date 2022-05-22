<?php

namespace backend\controllers;

use common\models\Expenseslist;
use Yii;
use common\models\Archive;
use common\models\ArchiveSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArchiveController implements the CRUD actions for Archive model.
 */
class ArchiveController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                //'only'  => ['login', 'logout', 'index'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions'=>['login'],
                        'roles'   => ['?'],
                    ],
                    [
                        'allow'         => true,
                        'roles'         => ['admin', 'manager'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->can('perm_create-event');
                        },
                    ],
                    [
                        'allow'        => false,
                        'roles'        => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            Yii::$app->user->logout();
                            Yii::$app->session->setFlash('denied', Yii::$app->params['error']['access-is-denied']);
                            return $this->redirect(['site/login']) ;
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Archive models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ArchiveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*$query = Expenseslist::find()
            ->select(
                [
                    'SUM(price) as price',
                    'title',
                    'expenses_id',
                    'FROM_UNIXTIME(expenseslist.created_at,"%m-%Y") as created_at'
                ]
            )
            ->joinWith(
                [
                    'expenses' => function ($q) {
                        $q->select(
                            [
                                'id',
                                'title'
                            ]
                        );
                }
                ]
            )
            ->groupBy(['expenses.title', 'created_at',])
            ->asArray();
        $expenses =  new ActiveDataProvider(
            [
                'query'      =>$query
    ]);*/

        return $this->render(
            'index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]
        );
    }

    /**
     * Displays a single Archive model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render(
            'view', [
            'model' => $this->findModel($id),
        ]
        );
    }

    /**
     * Creates a new Archive model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Archive();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render(
            'create', [
            'model' => $model,
        ]
        );
    }

    /**
     * Updates an existing Archive model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render(
            'update', [
            'model' => $model,
        ]
        );
    }

    /**
     * Deletes an existing Archive model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Archive model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Archive the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Archive::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}
