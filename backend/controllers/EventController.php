<?php

namespace backend\controllers;

use Yii;
use common\models\Event;
use common\models\EventSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * EventController implements the CRUD actions for Event model.
 */
class EventController extends AdminController
{
    /**
     * {@inheritdoc}
     */
    /*public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }*/

    /**
     * Lists all Event models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        /*$searchModel = new EventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);*/

        $events = Event::find()->with(['master','client'])->all();
//        $tasks  = [];
        foreach ($events as $item) {
            $event              = new \yii2fullcalendar\models\Event();
            $event->id          = $item->id;
            $event->title       = $item->client->username;
            $event->nonstandard = [
                'description' =>$item->description,
                'master_name' => $item->master->username,
            ];
            $event->color       = $item->master->color;
            $event->start       = $item->event_time_start;
            $event->end         = $item->event_time_end;

            $events[] = $event;
        }
        /*echo '<pre>';
        var_dump($events);
        die();*/
        return $this->render(
            'index',
            [
                'events' => $events,
//            'dataProvider' => $dataProvider,
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
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @param $date
     *
     * @return mixed
     */
    public function actionCreate($date)
    {
        $model                   = new Event();
        $model->event_time_start = $date;
        $model->event_time_end   = $date;

        if ($model->load(Yii::$app->request->post())) {
            $isValid = $model->validate();
            if (Yii::$app->request->isAjax && $isValid) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->save(false);
                return $this->redirect('index');
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
     * @param  integer  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $events = $this->findModel($id);
        if ($events->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($events);
            } else {
                $events->save(false);
                return $this->redirect('index');
            }
        }
        return $this->renderAjax(
            'update',
            [
                'model' => $events,
            ]
        );
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param  integer  $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Event model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param  integer  $id
     *
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Event::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
