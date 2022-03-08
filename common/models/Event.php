<?php

namespace common\models;

use backend\modules\telegram\models\Telegram;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string|null $master
 * @property string|null $description
 * @property-read \yii\db\ActiveQuery $client
 * @property string|null $notice
 */
class Event extends ActiveRecord
{

    public $totalEvent;
    public $checkEvent;
    public $service_array;

    /*  public static function getHistory()
      {
          $archive = EventService::find()
              ->select(['event_service.id', 'event_id', 'service_id', 'SUM(service.cost) as amount','event.master_id'])
              ->joinWith(
                  [
                      'event' => function ($q) {
                          $q->select(['event.id', 'master_id','DATE_FORMAT(event_time_start,"%Y-%b") as event_time_start'])
                              ->with(['eventService', 'services'])
                              ->where(['like','event_time_start','2022-02']);
                          //->groupBy(['master_id']);
                      },
                  ]
              )
              ->joinWith(
                  [
                      'service' => function ($q) {
                          $q->select(['service.id', 'name','cost'])
                              ->distinct()
                              ->groupBy(['name']);
                      },
                  ]
              )
              ->joinWith(
                  [
                      'event.master' => function ($q) {
                          $q->select(['id', 'username'])
                           ->with(['rates']);
                      }
                  ]
              )
              ->groupBy(['event.master_id'])
              ->asArray()
              ->all();

          return $archive;
      }*/


    /**
     *
     * @return array[]
     */
    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'event';
    }

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['master_id'], 'required', 'message' => 'Выберите мастера'],
            [['client_id'], 'required', 'message' => 'Выберите клиента'],
            //[['service_array'], 'required', 'message' => 'Выберите услуги'],
            [['service_array'], 'safe'],
            ['master_id', 'filter', 'filter' => 'intval'],
            ['client_id', 'filter', 'filter' => 'intval'],
            [['event_time_start', 'event_time_end', 'created_at', 'updated_at', 'checkEvent'], 'safe'],
            [['notice'], 'string', 'max' => 255],
            //['checkEvent', 'validateEvent', 'skipOnEmpty' => false, 'skipOnError' => false]

        ];
    }

    /*public function validateEvent()
    {
        $old_model = Event::find()
            ->with('client', 'master')
            ->select('event_time_start, master_id, client_id')
            ->where(
                [
                    'event_time_start' => $this->event_time_start,
                    'master_id'        => $this->master_id
                ]
            )
            ->asArray()
            ->one();


        if ($this->isNewRecord) {
            if (
                date('Y-m-d H:i', strtotime($old_model['event_time_start'])) == $this->event_time_start &&
                $old_model['master_id'] == $this->master_id
            ) {
                $this->addError(
                    'checkEvent',
                    'Мастер ' . $this['master']['username'] . ' занят в это время'
                );
            }
            return true;
        }


        if (!empty($old_model)) {
            if (
                date('Y-m-d H:i:s', strtotime($old_model['event_time_start'])) == $this->event_time_start &&
                $old_model['master_id'] == $this->master_id &&
                !$this->isAttributeChanged('client_id') ||
                date('Y-m-d H:i', strtotime($old_model['event_time_start'])) == $this->event_time_start &&
                $old_model['master_id'] == $this->master_id &&
                !$this->isAttributeChanged('client_id')
            ) {
                $this->addError(
                    'checkEvent',
                    'Мастер ' . $this['master']['username'] . ' занят в это время'
                );
            } else {
                return true;
            }
        }
        return true;
    }*/


    /**
     * {@inheritdoc}
     */
    public
    function attributeLabels(): array
    {
        return [
            'id'               => 'ID',
            'client_id'        => 'Клиент',
            'master_id'        => 'Мастер',
            'description'      => 'Услуги',
            'service_array'    => 'Услуги',
            'salary'           => 'З/П',
            'notice'           => 'Пожелания',
            'event_time_start' => 'Дата',
            'event_time_end'   => 'Время окончания',
        ];
    }

    /**
     * Gets query for [[Master]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaster(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'master_id']);
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'client_id']);
    }

    /**
     * Gets query for [[Telegram]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTelegram(): ActiveQuery
    {
        return $this->hasOne(Telegram::class, ['user_id' => 'client_id']);
    }

    /**
     * Relationship with [[event_service]] table
     *
     * @return \yii\db\ActiveQuery
     */

    public function getEventService(): ActiveQuery
    {
        return $this->hasMany(EventService::class, ['event_id' => 'id']);
    }

    /**
     * Relationship with [[Service]] table
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServices(): ActiveQuery
    {
        return $this->hasMany(Service::class, ['id' => 'service_id'])->via('eventService');
    }


    public function afterFind()
    {
        $this->service_array = $this->services;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        //$arr = array_keys($this->services);
        $arr = ArrayHelper::map($this->services, 'id', 'id');

        if ($this->service_array) {
            foreach ($this->service_array as $one) {
                if (!in_array($one, $arr)) {
                    $model             = new EventService();
                    $model->event_id   = $this->id;
                    $model->service_id = $one;
                    $model->save();
                }
                if (isset($arr[$one])) {
                    unset($arr[$one]);
                }
            }
            EventService::deleteAll(['service_id' => $arr, 'event_id' => $this->id]);
        }
        EventService::deleteAll(['service_id' => $arr, 'event_id' => $this->id]);
    }

    /**
     * Getting records for masters
     *
     * @param int $id
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findMasterEvents(
        int $id
    ): ActiveQuery {
        /*$dependency = Yii::createObject(
            [
                'class' => 'yii\caching\DbDependency',
                'sql' => 'SELECT MAX(updated_at) FROM event',
                'reusable' => true
            ]
        );
        return Event::getDb()->cache(
            function () use ($id) {
                return Event::find()->with(['master', 'client'])->where(['master_id' => $id])->andWhere(
                    'event_time_start >= DATE(NOW())'
                )->orderBy(
                    ['event_time_start' => SORT_ASC]
                )->asArray();
            },
            3600,
            $dependency
        );*/
        return Event::find()->with(['master', 'client', 'services'])
            ->where(['master_id' => $id])
            ->andWhere('event_time_start >= DATE(NOW())')
            ->orderBy(['event_time_start' => SORT_ASC])
            ->asArray();
    }

    /**
     * Getting records for manager
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findManagerEvents(): ActiveQuery
    {
        /*$dependency = Yii::createObject(
            [
                'class' => 'yii\caching\DbDependency',
                'sql' => 'SELECT MAX(updated_at) FROM event',
                'reusable' => true
            ]
        );
        return Event::getDb()->cache(
            function () {
                return Event::find()->with(['master', 'client'])->where('event_time_start >= DATE(NOW())')->orderBy(
                    [
                        'event_time_start'
                        => SORT_ASC
                    ]
                )
                    ->asArray();
            },
            3600,
            $dependency
        );*/

        return Event::find()->with(['master', 'client', 'services', 'eventService'])
            ->where('event_time_start >= DATE(NOW())')
            ->orderBy(
                [
                    'event_time_start'
                    => SORT_ASC
                ]
            )
            ->asArray();
    }

    /**
     * Getting records for client
     *
     * @param int $id
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findClientEvents(int $id): ActiveQuery
    {
        /*$dependency = Yii::createObject(
            [
                'class' => 'yii\caching\DbDependency',
                'sql' => 'SELECT MAX(updated_at) FROM event',
                'reusable' => true
            ]
        );
        return Event::getDb()->cache(
            function () use ($id) {
                return Event::find()->with(['master', 'client'])->select(
                    ['id', 'client_id', 'master_id', 'description', 'event_time_start']
                )
                    ->where(
                        ['client_id' => $id]
                    )->asArray();
            },
            3600,
            $dependency
        );*/
        return Event::find()->with(['master', 'client', 'services', 'eventService'])
            ->select(['id', 'client_id', 'master_id', 'description', 'event_time_start'])
            ->where(['client_id' => $id])
            ->andWhere('event_time_start >= DATE(NOW())')
            ->orderBy(
                [
                    'event_time_start'
                    => SORT_ASC
                ]
            )
            ->asArray();
    }

    /**
     * Returns a list of future records
     *
     * @param $user_id
     *
     * @return array|\common\models\Event[]|\yii\db\ActiveRecord[]
     */
    public static function findNextClientEvents($user_id): array
    {
        return Event::find()
            ->select('event_time_start, description')
            ->where(['client_id' => $user_id])
            ->andWhere(['>', 'event_time_start', new Expression('CURDATE()')])
            ->orderBy(['event_time_start' => SORT_ASC])
            ->asArray()
            ->all();
    }


    /**
     * Returns a list of previous records
     *
     * @param $user_id
     *
     * @return array|\common\models\Event[]|\yii\db\ActiveRecord[]
     */
    public static function findPreviousClientEvents(
        $user_id
    ): array {
        return Event::find()
            ->select('event_time_start, description')
            ->where(['client_id' => $user_id])
            ->andWhere(['<', 'event_time_start', new Expression('CURDATE()')])
            ->orderBy(['ABS' => new Expression('DATEDIFF(NOW(), `event_time_start`)')])
            ->limit(2)
            ->asArray()
            ->all();
    }

    /**
     * The total number of entries in the calendar
     *
     * @return bool|int|string|null
     */
    public static function countEventTotal($masterIds) {
        $dependency = Yii::createObject(
            [
                'class'    => 'yii\caching\DbDependency',
                'sql'      => 'SELECT MAX(updated_at) FROM event',
                'reusable' => true
            ]
        );
        return Event::getDb()->cache(
            function () use ($masterIds) {
                return Event::find()->where(['>=', 'event_time_start', date('Y-m-d')])
                    ->andWhere(['master_id' => $masterIds])
                    ->count('client_id');
            },
            3600,
            $dependency
        );
    }

    /**
     * Total number of records for each master
     *
     * @param $masterIds
     *
     * @return array
     */
    public function getTotalEventsMaster(
        $masterIds
    ): array {
        $dependency = Yii::createObject(
            [
                'class'    => 'yii\caching\DbDependency',
                'sql'      => 'SELECT MAX(updated_at) FROM event',
                'reusable' => true
            ]
        );
        return Event::getDb()->cache(
            function () use ($masterIds) {
                return Event::find()->select(['COUNT(client_id) AS totalEvent', 'master_id'])
                    ->with(
                        [
                            'master' => function ($q) {
                                $q->select(['id', 'username', 'avatar']);
                            }
                        ]
                    )
                    ->where(['master_id' => $masterIds])
                    ->andWhere(['>=', 'event_time_start', date('Y-m-d')])
                    ->groupBy('master_id')
                    ->all();
            },
            3600,
            $dependency
        );
    }


    /**
     * Return event list dataProvider
     *
     * @return \yii\data\ActiveDataProvider
     * @throws \Throwable
     * @throws InvalidConfigException
     */
    public static function getEventDataProvider(
        $userId
    ): ActiveDataProvider {
        if (Yii::$app->user->can('manager')) {
            $query = Event::findManagerEvents();
        } elseif (Yii::$app->user->can('master')) {
            $query = Event::findMasterEvents($userId);
        } else {
            $query = Event::findClientEvents($userId);
        }

        $dataProvider    = new ActiveDataProvider(
            [
                'query'      => $query,
                'pagination' => false,
            ]
        );
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

        Yii::$app->db->cache(
            function () use ($dataProvider) {
                $dataProvider->prepare();
            },
            3600,
            $dependency
        );

        return $dataProvider;
    }

    /**
     * Getting the name of the service
     * @param $data
     * @return string
     */
    public static function getServiceString($data): string
    {
        $servicesName = '';

        foreach ($data as $service) {
            $servicesName .= $service['name'] . '</br>';
        }
        if ($servicesName == null) {
            $servicesName = 'Услуга не указана'; // TODO вынести в параметры
        }

        return $servicesName;
    }

    /**
     * Total amount for services
     * @param $dataProvider
     * @return string
     */
    public static function getTotal($dataProvider): string
    {
        $total = 0;
        foreach ($dataProvider->models as $model) {
            foreach ($model->service_array as $cost) {
                $total += $cost->cost;
            }
        }


        return $total;
    }

    /**
     * Employee remuneration
     * @param $dataProvider
     * @return string
     * @throws InvalidConfigException
     */
    public static function getSalary($dataProvider): string
    {
        $total = 0;

        foreach ($dataProvider as $model) {
            foreach ($model->master->rates as $master) {
                foreach ($model->services as $service) {
                    if ($master->service_id == $service->id && $master->rate < 100) {
                        $total += $service->cost * ($master->rate / 100);
                    }
                }
            }
        }

        return $total;
    }


    /**
     * Label data for the chart
     * @param $dataProvider
     * @return array
     */
    public static function getlabelsCharts($dataProvider): array
    {
        $labels = [];

        foreach ($dataProvider->models as $model) {
            foreach ($model->services as $key => $item) {
                if (!in_array($item->name, $labels)) {
                    $labels[$item->id] .= $item->name;
                }
            }
        }

        return array_values($labels);
    }

    /**
     * Data for the chart
     * @param $dataProvider
     * @return array
     */
    public static function getDataCharts($dataProvider): array
    {
        $amount = [];

        foreach ($dataProvider->models as $model) {
            foreach ($model->master->rates as $rate) {
                foreach ($model->services as $item) {
                    if (!in_array($item->name, $amount)) {
                        if ($rate->service_id == $item->id) {
                            $amount[$item->id] += ($item->cost * $rate->rate) / 100;
                        }
                    }
                }
            }
        }
        return array_values($amount);
    }

    /**
     * Forming the list of existing/absent master rates
     * @param $model - each master's data for each entry
     * @return string - list of services
     */
    public static function MissingMasterRates($model): string
    {
        $rates = ArrayHelper::getColumn($model->master->rates, 'service_id');

        $events   = [];
        $event_id = [];
        foreach ($model->services as $item) {
            $events[$item['id']] .= $item['name'];
            $event_id[]          .= $item['id'];
        }


        $no_set_rate    = array_diff($event_id, $rates);
        $isset_set_rate = array_intersect($event_id, $rates);


        $no_rate = [];
        foreach ($no_set_rate as $no) {
            foreach ($events as $key => $event) {
                if ($no == $key) {
                    $no_rate[] .= "<span class='text-danger'>$event</span></br>";
                }
            }
        }
        $isset_rate = [];
        foreach ($isset_set_rate as $isset) {
            foreach ($events as $key => $event) {
                if ($isset == $key) {
                    $isset_rate[] .= "<span class='text-green'>$event</span></br>";
                }
            }
        }

        $all_events_names = ArrayHelper::merge($isset_rate, $no_rate);

        $list_all_event_name = '';
        foreach ($all_events_names as $event_name) {
            $list_all_event_name .= $event_name;
        }
        return $list_all_event_name;
    }

    /**
     * Sampling of monthly service data
     *
     * @param $params - date range
     * @return ActiveDataProvider
     */
    public static function getHistoryData($params): ActiveDataProvider
    {
        return new ActiveDataProvider(
            [
                'query'      => EventService::find()
                    ->select(
                        [
                            'event_service.id',
                            'event_id',
                            'service_id',
                            'SUM(service.cost) as amount',
                            'event.master_id',
                            'event.event_time_start'
                        ]
                    )
                    ->joinWith(
                        [
                            'event' => function ($q) {
                                $q->select(
                                    [
                                        'event.id',
                                        'master_id',
                                        'DATE_FORMAT(event_time_start,"%Y-%b") as event_time_start'
                                    ]
                                )
                                    ->with(['eventService', 'services']);
                            },
                        ]
                    )
                    ->joinWith(
                        [
                            'service' => function ($q) {
                                $q->select(['service.id', 'name', 'cost'])
                                    ->distinct()
                                    ->groupBy(['name']);
                            },
                        ]
                    )
                    ->joinWith(
                        [
                            'event.master' => function ($q) {
                                $q->select(['id', 'username'])
                                    ->with(['rates']);
                            }
                        ]
                    )
                    ->andFilterWhere(
                        [
                            '>=',
                            'event.event_time_start',
                            $params['from_date'] ? $params['from_date'] . ' 00:00:00' : null
                        ]
                    )
                    ->andFilterWhere(
                        ['<=', 'event.event_time_start', $params['to_date'] ? $params['to_date'] . ' 23:59:59' : null]
                    )
                    ->groupBy(['DATE_FORMAT(event.event_time_start,"%Y-%b")', 'event.master_id'])
                    ->orderBy(['event.event_time_start'=>SORT_ASC])
                    ->asArray(),
                'pagination' => false
            ]
        );
    }

    /**
     * Saving the history of services for the month in the database
     * @param $dataProvider
     * @return bool
     * @throws InvalidConfigException
     */
    public static function saveHistoryData($dataProvider): bool
    {
        $flag = false;
        foreach ($dataProvider->models as $value) {
            foreach ($value['event']['master']['rates'] as $rate) {
                if ($value['service_id'] == $rate['service_id']) {
                    $archive             = new Archive();
                    $archive->user_id    = $value['event']['master_id'];
                    $archive->service_id = $value['service_id'];
                    $archive->amount     = $value['amount'];
                    $archive->salary     = $value['amount'] * $rate['rate'] / 100;
                    $archive->date       = Yii::$app->formatter->asDate(
                        $value['event']['event_time_start'],
                        'php: m-Y'
                    );
                    if ($archive->save()) {
                        $flag = true;
                    }
                }
            }
        }
        if ($flag) {
            return true;
        }
        return false;
    }
}
