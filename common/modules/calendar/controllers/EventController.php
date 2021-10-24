<?php

namespace common\modules\calendar\controllers;

use common\components\behaviors\DeleteCacheBehavior;
use Yii;
use common\models\Event;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['login', 'logout', 'index'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['login'],
                        'roles'   => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            [
                'class'     => DeleteCacheBehavior::class,
                'cache_key' => ['events_list'],
                'actions'   => ['create', 'update', 'delete'],
            ],
        ];
    }

    /**
     * Lists all Event models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $cache = Yii::$app->cache;
        $key   = 'events_list';  // Формируем ключ
        // Данный метод возвращает данные либо из кэша, либо из откуда-либо и записывает их в кэш по ключу на 1 час
        $dependency = Yii::createObject(
            [
                'class' => 'yii\caching\DbDependency',
                'sql'   => 'SELECT MAX(updated_at) FROM event',
            ]
        );
        $events     = $cache->getOrSet(
            $key,
            function () {
                return Event::find()->with(['master', 'client'])->all();
            },
            3600,
            $dependency
        );

        foreach ($events as $item) {
            $event              = new \yii2fullcalendar\models\Event();
            $event->id          = $item->id;
            $event->title       = $item->client->username;
            $event->nonstandard = [
                'description' => $item->description,
                'notice' => $item->notice,
                'master_name' => $item->master->username,
            ];
            $event->backgroundColor       = $item->master->color;
            $event->start       = $item->event_time_start;
            $event->end         = $item->event_time_end;

            $events[] = $event;
        }

        return $this->render(
            'index',
            [
                'events' => $events,
            ]
        );
    }

    /**
     * Displays a single Event model.
     *
     * @param  integer  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax(
            'view',
            [
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Creates a new Event model.
     *
     * @param $start
     * @param  $end
     *
     * @return mixed
     */
    public function actionCreate($start, $end)
    {
        $model                   = new Event();
        $model->event_time_start = $start;
        $model->event_time_end   = $end;


        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax && $model->validate() || $model->hasErrors()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->save(false);
                Yii::$app->session->setFlash('msg', "Запись ".$model->client->username. " сохранена");
                return $this->redirect('/admin/calendar/event/index');
            }
        }


        return $this->renderAjax(
            'create',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Updates an existing Event model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param  int  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $events = $this->findModel($id);

        if ($events->load(Yii::$app->request->post())) {

            if (Yii::$app->request->isAjax && $events->validate()  || $events->hasErrors()) {

                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($events);
            } else {
                $events->save(false);
                return $this->redirect('/admin/calendar/event/index');
            }
        }
        return $this->renderAjax(
            'update',
            [
                'model' => $events,
            ]
        );
    }


    public function actionUpdateResize($id, $start, $end)
    {
        $model = $this->findModel($id);
        $model->event_time_start = $start;
        $model->event_time_end   = $end;
        $model->save(false);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdateDrop($id, $start, $end)
    {
        $model = $this->findModel($id);
        $model->event_time_start = $start;
        $model->event_time_end   = $end;

        $model->save(false);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param  int  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param  int  $id
     *
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
