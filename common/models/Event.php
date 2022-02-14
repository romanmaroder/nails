<?php

namespace common\models;

use backend\modules\telegram\models\Telegram;
use common\modules\calendar\controllers\EventController;
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
            ['checkEvent', 'validateEvent', 'skipOnEmpty' => false, 'skipOnError' => false]

        ];
    }

    public function validateEvent()
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
    }


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
            'cost'             => 'Цена',
            'salary'           => 'Вознаграждение',
            'notice'           => 'Пожелания',
            'event_time_start' => 'Время начала',
            'event_time_end'   => 'Время окончания',
        ];
    }

    /**
     * Gets query for [[Master]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getMaster(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'master_id']);
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getClient(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'client_id']);
    }

    /**
     * Gets query for [[Telegram]].
     *
     * @return \yii\db\ActiveQuery
     */
    public
    function getTelegram(): ActiveQuery
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


    /**
     * Relationship with [[ServiceUser]] table
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getRates(): ActiveQuery
     {
         return $this->hasMany(ServiceUser::class, ['user_id' => 'master_id']);
     }*/

    /**
     * Relationship with [[service]] table
     *
     * @return \yii\db\ActiveQuery
     */
    /*public function getRate(): ActiveQuery
    {
        return $this->hasMany(Service::class, ['id' => 'service_id'])->via('rates');
    }*/


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
                    $model = new EventService();
                    $model->event_id = $this->id;
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
    public
    static function findMasterEvents(
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
    public
    static function findManagerEvents(): ActiveQuery
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
    public
    static function findClientEvents(
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
    public
    static function findPreviousClientEvents(
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
    public
    static function countEventTotal(
        $masterIds
    ) {
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
    public
    function getTotalEventsMaster(
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
    public
    static function getEventDataProvider(
        $userId
    ): ActiveDataProvider {
        if (Yii::$app->user->can('manager')) {
            $query = Event::findManagerEvents();
        } elseif (Yii::$app->user->can('master')) {
            $query = Event::findMasterEvents($userId);
        } else {
            $query = Event::findClientEvents($userId);
        }

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $query,
                'pagination' => false,
            ]
        );
        $eventDependency = new DbDependency(['sql' => 'SELECT MAX(updated_at) FROM event']);
        $userDependency = new DbDependency(['sql' => 'SELECT MAX(updated_at) FROM user']);
        $dependency = Yii::createObject(
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
        $services_name = '';

        foreach ($data as $service) {
            $services_name .= $service['name'] . '</br>';
        }
        if ($services_name == null) {
            $services_name = 'Услуга не указана'; // TODO вынести в параметры
        }

        return $services_name;
    }

    /**
     * Total amount for services
     * @param $dataProvider
     * @return string
     * @throws InvalidConfigException
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


    public static function getlabelsCharts($dataProvider): array
    {
        $labels = [];

        foreach ($dataProvider->models as $model) {
            foreach ($model->services as $key => $item) {
                if (!in_array($item->name, $labels)) {
                    $labels[$item->name] .= $item->name;
                }
            }
        }

        return array_values($labels);
    }

    public static function getDataCharts($dataProvider): array
    {
        $amount = [];

        foreach ($dataProvider->models as $model) {
            foreach ($model->master->rates as $rate) {
                foreach ($model->services as $item) {
                    if (!in_array($item->name, $amount)) {
                        //if ($rate->rate < 100){
                        $amount[$item->name] += $item->cost * $rate->rate / 100;
                        //}
                    }
                }
            }
        }

        /*echo '<pre>';
        var_dump($model->eventService);
        die();*/
        return array_values($amount);
    }


    public static function getUserEventService($userid, $id)
    {
        $events = self::find()->joinWith(['eventService', 'services', 'master.rates'])->where(
            ['master_id' => $userid, 'event.id' => $id]
        )->asArray()->all();

        $event = ArrayHelper::getColumn($events, 'eventService');
        $events_ids = ArrayHelper::getColumn($events, 'id');
        $services = ArrayHelper::getColumn($events, 'services');
        $master_rates = ArrayHelper::getColumn($events, 'master.rates');

        $service_ids = [];
        foreach ($services as $key => $value) {
            foreach ($value as $item) {
                $service_ids[$key] = $item['id'];
            }
        }

        $event_service_ids = [];
        $event_ids = [];
        foreach ($event as $key => $value) {
            foreach ($value as $item) {
                $event_service_ids[] = $item['service_id'];
                $event_ids[] = $item['event_id'];
            }
        }


        $master_rates_service_ids = [];
        foreach ($master_rates as $value) {
            foreach ($value as $key => $item) {
                $master_rates_service_ids[$key] = $item['service_id'];
            }
        }

        $no_set_rate = array_diff($event_service_ids, $master_rates_service_ids);

        if (!in_array($no_set_rate, $service_ids)) {
            $no_set_rate_ids = '';
            foreach ($services as $service_arr) {
                foreach ($no_set_rate as $rate) {
                    foreach ($service_arr as $service) {
                        foreach ($event_ids as $item) {
                            foreach ($events_ids as $event_id) {
                                if ($rate == $service['id'] && $item == $event_id) {
                                    $no_set_rate_ids = '<span class="text-danger">Ставка 0%</span>';
                                }
                            }
                        }
                    }
                }
            }
            return $no_set_rate_ids;
        }
    }


}
