<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\caching\DbDependency;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

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

    /**
     *
     * @return array[]
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
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
            ['master_id', 'filter', 'filter' => 'intval'],
            ['client_id', 'filter', 'filter' => 'intval'],
            [['description'], 'string'],
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
                    'master_id' => $this->master_id
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
            'id' => 'ID',
            'client_id' => 'Клиент',
            'master_id' => 'Мастер',
            'description' => 'Что делаем',
            'notice' => 'Пожелания',
            'event_time_start' => 'Время начала',
            'event_time_end' => 'Время окончания',
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
        return Event::find()->with(['master', 'client'])
            ->where(['master_id' => $id])
            ->andWhere('event_time_start >= DATE(NOW())')
            ->orderBy( ['event_time_start' => SORT_ASC])
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

        return Event::find()->with(['master', 'client'])
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
        return Event::find()->with(['master', 'client'])
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
    public static function findNextClientEvents( $user_id ): array
    {
        return Event::find()
            ->select('event_time_start, description')
            ->where(['client_id' => $user_id])
            ->andWhere(['>', 'event_time_start', new Expression('CURDATE()')])
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
                'class' => 'yii\caching\DbDependency',
                'sql' => 'SELECT MAX(updated_at) FROM event',
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
                'class' => 'yii\caching\DbDependency',
                'sql' => 'SELECT MAX(updated_at) FROM event',
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
     * @throws \yii\base\InvalidConfigException
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
                'query' => $query,
                'pagination' => false,
            ]
        );
        $eventDependency= new DbDependency(['sql'=>'SELECT MAX(updated_at) FROM event']);
        $userDependency= new DbDependency(['sql'=>'SELECT MAX(updated_at) FROM user']);
        $dependency = Yii::createObject(
            [
                'class' => 'yii\caching\ChainedDependency',
                'dependOnAll'=>true,
                'dependencies'=>[
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
}
