<?php

namespace common\modules\calendar\controllers;

use backend\modules\messenger\controllers\AppMessenger;
use backend\modules\telegram\api\TelegramBot;
use backend\modules\telegram\models\Telegram;
use backend\modules\viber\api\ViberBot;
use backend\modules\viber\models\Viber;
use common\components\behaviors\DeleteCacheBehavior;
use common\models\EventSearch;
use common\models\Expenseslist;
use common\models\ExpenseslistSearch;
use common\models\ServiceUser;
use Viber\Api\Message\Text;
use Viber\Api\Sender;
use Yii;
use common\models\Event;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
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
                    'delete' => ['POST','GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                //'only'  => ['login', 'logout', 'index'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions'=>['login','user-service'],
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
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        /*echo'<pre>';
        var_dump(Event::find()->with(['master', 'client', 'services'])->all());
        die();*/
        $cache = Yii::$app->cache;
        $key   = 'events_list';  // Формируем ключ
        // Данный метод возвращает данные либо из кэша, либо из откуда-либо и записывает их в кэш по ключу на 1 час
        $eventDependency = new DbDependency(['sql' => 'SELECT MAX(updated_at) FROM event']);
        $userDependency  = new DbDependency(['sql' => 'SELECT MAX(updated_at) FROM user']);
        $dependency      = Yii::createObject(
            [
                'class'        => 'yii\caching\ChainedDependency',
                'dependOnAll'  => true,
                'dependencies' => [
                    $eventDependency,
                    $userDependency
                ],
            ]
        );
        $events          = $cache->getOrSet(
            $key,
            function () {
                return Event::find()->with(['master', 'client', 'services'])->all();
            },
            3600,
            $dependency
        );

        foreach ($events as $item) {
            $event                  = new \yii2fullcalendar\models\Event();
            $event->id              = $item->id;
            $event->title           = $item->client->username;
            $event->nonstandard     = [
                'description' => Event::getServiceName($item->services) ? Event::getServiceName(
                    $item->services
                ) : $item->description,
                'notice'      => $item->notice,
                'master_name' => $item->master->username,
            ];
            $event->backgroundColor = $item->master->color;
            $event->start           = $item->event_time_start;
            $event->end             = $item->event_time_end;

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
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id)
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


                $chat    = Telegram::find()->where(['user_id' => $model->client_id])->asArray()->one();
                $chat_id = $chat['chat_id'];

                if ($chat_id) {
                    $telegram_bot = new TelegramBot(Yii::$app->params['telegramToken']);

                    $telegram_bot->sendMessage(
                        [
                            'chat_id' => $chat_id,
                            'text'    => Yii::$app->smsSender->checkTimeOfDay() . 'Дата следующей записи '
                                . Yii::$app->formatter->asDatetime($model->event_time_start, 'php:d M Y на H:i'),
                        ]
                    );
                }

                $viber    = Viber::find()->where(['user_id' => $model->client_id])->asArray()->one();
                $viber_id = $viber['viber_user_id'];

                if ($viber_id) {
                    $viber_bot = new ViberBot(['token' => Yii::$app->params['viber']['viberToken']]);

                    $botSender = new Sender(
                        [
                            'name'   => Yii::$app->params['viber']['viberBotName'],
                            'avatar' => Yii::$app->params['viber']['viberBotAvatar'],
                        ]
                    );
                    $viber_bot->getClient()->sendMessage(
                        (new Text())
                            ->setSender($botSender)
                            ->setReceiver($viber_id)
                            ->setMinApiVersion(3)
                            ->setText(
                                Yii::$app->smsSender->checkTimeOfDay() . 'Дата следующей записи '
                                . Yii::$app->formatter->asDatetime($model->event_time_start, 'php:d M Y на H:i')
                            )
                            ->setKeyboard(
                                (new \Viber\Api\Keyboard())
                                    ->setButtons(
                                        [
                                            (new \Viber\Api\Keyboard\Button())
                                                ->setColumns('3')
                                                ->setBgColor('#7f8c8d')
                                                ->setTextSize('regular')
                                                ->setActionType('reply')
                                                ->setActionBody('next')
                                                ->setText('Следующие'),
                                            (new \Viber\Api\Keyboard\Button())
                                                ->setColumns('3')
                                                ->setBgColor('#7f8c8d')
                                                ->setTextSize('regular')
                                                ->setActionType('reply')
                                                ->setActionBody('previous')
                                                ->setText('Предыдущие')
                                        ]
                                    )
                            )
                    );
                }

                Yii::$app->session->setFlash('msg', "Запись " . $model->client->username . " сохранена");
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
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $events = $this->findModel($id);

        if ($events->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax && $events->validate() || $events->hasErrors()) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($events);
            } else {
                $events->save(false);

                $chat    = Telegram::find()->where(['user_id' => $events->client_id])->asArray()->one();
                $chat_id = $chat['chat_id'];
                if ($chat_id) {
                    $telegram_bot = new TelegramBot(Yii::$app->params['telegramToken']);
                    $telegram_bot->sendMessage(
                        [
                            'chat_id' => $chat_id,
                            'text'    => Yii::$app->smsSender->checkTimeOfDay(
                                ) . 'Дата записи изменена ' . Yii::$app->formatter->asDatetime(
                                    $events->event_time_start,
                                    'php:d M Y на H:i'
                                ),
                        ]
                    );
                }

                $viber    = Viber::find()->where(['user_id' => $events->client_id])->asArray()->one();
                $viber_id = $viber['viber_user_id'];
                if ($viber_id) {
                    $viber_bot = new ViberBot(['token' => Yii::$app->params['viber']['viberToken']]);
                    $botSender = new Sender(
                        [
                            'name'   => Yii::$app->params['viber']['viberBotName'],
                            'avatar' => Yii::$app->params['viber']['viberBotAvatar'],
                        ]
                    );
                    $viber_bot->getClient()->sendMessage(
                        (new Text())
                            ->setSender($botSender)
                            ->setReceiver($viber_id)
                            ->setMinApiVersion(3)
                            ->setText(
                                Yii::$app->smsSender->checkTimeOfDay() . 'Дата следующей записи '
                                . Yii::$app->formatter->asDatetime($events->event_time_start, 'php:d M Y на H:i')
                            )
                            ->setKeyboard(
                                (new \Viber\Api\Keyboard())
                                    ->setButtons(
                                        [
                                            (new \Viber\Api\Keyboard\Button())
                                                ->setColumns('3')
                                                ->setBgColor('#7f8c8d')
                                                ->setTextSize('regular')
                                                ->setActionType('reply')
                                                ->setActionBody('next')
                                                ->setText('Следующие'),
                                            (new \Viber\Api\Keyboard\Button())
                                                ->setColumns('3')
                                                ->setBgColor('#7f8c8d')
                                                ->setTextSize('regular')
                                                ->setActionType('reply')
                                                ->setActionBody('previous')
                                                ->setText('Предыдущие')
                                        ]
                                    )
                            )
                    );
                }

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


    /**
     * Updating the record date by changing the event size
     * @param $id - user identifier
     * @param $start - start time
     * @param $end - end time
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateResize($id, $start, $end)
    {
        $model                   = $this->findModel($id);
        $model->event_time_start = $start;
        $model->event_time_end   = $end;
        $model->save(false);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['index']);
        }

        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Updating the record date by dragging and dropping an event
     * @param $id - user identifier
     * @param $start - start time
     * @param $end - end time
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateDrop($id, $start, $end)
    {
        $model                   = $this->findModel($id);
        $model->event_time_start = $start;
        $model->event_time_end   = $end;

        $model->save(false);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['index']);
        }

        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Deletes an existing Event model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
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
     * @param int $id
     *
     * @return Event the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id)
    {

        if (($model = Event::find()->with('services')->andwhere(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }

    /**
     * Displaying user statistics
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStatistic()
    {
        $searchModel = new EventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $totalEvent = Event::getTotal($dataProvider);
        $totalSalary = Event::getSalary($dataProvider->models);
        $chartEventLabels = Event::getlabelsCharts($dataProvider);
        $chartEventData = Event::getDataCharts($dataProvider);

        $searchModelExpenseslist = new ExpenseslistSearch();
        $dataProviderExpenseslist = $searchModelExpenseslist->search(Yii::$app->request->queryParams);
        $chartExpensesLabels = Expenseslist::getlabelsCharts($dataProviderExpenseslist->models);
        $chartExpensesData = Expenseslist::getDataCharts($dataProviderExpenseslist);

        $dataHistory = $this->getHistory();

        if (Yii::$app->request->get('history') && empty(Yii::$app->request->get('archive'))) {
            Yii::$app->session->setFlash('info', Yii::$app->params['error']['date-range']);
        }
        if (Yii::$app->request->get('history') == 'save' && !empty(Yii::$app->request->get('archive'))) {
            if ($this->saveHistory()) {
                Yii::$app->session->setFlash(
                    'info',
                    'Данные за промежуток ' . Yii::$app->request->queryParams['from_date'] . ' - ' . Yii::$app->request->queryParams['to_date']
                    . ' сохранены'
                );
                return $this->redirect(['/calendar/event/statistic']);
            }
            Yii::$app->session->setFlash('info', Yii::$app->params['error']['error']);
            return $this->redirect(['/calendar/event/statistic']);
        }


        return $this->render(
            'statistic',
            [
                'searchModel'              => $searchModel,
                'dataProvider'             => $dataProvider,
                'totalEvent'               => $totalEvent,
                'totalSalary'              => $totalSalary,
                'chartEventLabels'         => $chartEventLabels,
                'chartEventData'           => $chartEventData,
                'dataHistory'              => $dataHistory,
                'dataProviderExpenseslist' => $dataProviderExpenseslist,
                'searchModelExpenseslist'  => $searchModelExpenseslist,
                'chartExpensesLabels'      => $chartExpensesLabels,
                'chartExpensesData'        => $chartExpensesData,

            ]
        );
    }

    /**
     * Returns an array of dates
     *
     * @return ActiveDataProvider
     */
    private function getHistory(): ActiveDataProvider
    {
        return Event::getHistoryData(Yii::$app->request->queryParams);
    }

    /**
     * Preserves history
     *
     * @return bool
     * @throws InvalidConfigException
     */
    private function saveHistory(): bool
    {
        if ($this->getHistory()) {
            return Event::saveHistoryData($this->getHistory());
        }
        return false;
    }

    /**
     * Getting a list of services of one master when making an appointment
     *
     * @param ?int $id - User ID
     * @return array|false|string
     * @throws NotFoundHttpException
     */
    public function actionUserService(?int $id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (!$id) {
                throw new NotFoundHttpException('Не найдено услуг для данного пользователя!');
            } else {
                return ServiceUser::getUserServices($id);
            }
        }
        return false;
    }
}
