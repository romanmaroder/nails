<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property string|null $client_id
 * @property string|null $master
 * @property string|null $description
 * @property string|null $notice
 */
class Event extends ActiveRecord
{

    public $totalEvent;
    /**
     * @var mixed|null
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

    public function rules(): array
    {
        return [
            [['master_id'], 'integer', 'message' => 'Выберите мастера'],
            [['client_id'], 'integer', 'message' => 'Выберите клиента'],
            [['client_id'], 'required', 'message' => 'Выберите клиента'],
            [['description'], 'string'],
            [['event_time_start', 'event_time_end','created_at','updated_at'], 'safe'],
            [['notice'], 'string', 'max' => 255],
            /*[
                ['master_id'],
                'exist',
                'skipOnError'     => false,
                'skipOnEmpty'     => false,
                'targetClass'     => Master::class,
                'targetAttribute' =>
                    ['master_id' => 'id'],
                'message'         => 'Такой мастер не работает у нас'
            ],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'               => 'ID',
            'client_id'        => 'Клиент',
            'master_id'        => 'Мастер',
            'description'      => 'Что делаем',
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
     * Getting records for masters
     *
     * @param  int  $id
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findMasterEvents(int $id): ActiveQuery
    {
        return Event::find()->with('client')->where(['master_id' => $id])->andWhere('event_time_start >= DATE(NOW())')->orderBy(
            ['event_time_start' => SORT_ASC]
        );
    }

    /**
     * Getting records for manager
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findManagerEvents(): ActiveQuery
    {
        return Event::find()->with('client')->where('event_time_start >= DATE(NOW())')->orderBy(
            [
                'event_time_start'
                => SORT_ASC
            ]
        );
            #->asArray();
    }

    /**
     * Getting records for client
     *
     * @param  int  $id
     *
     * @return \yii\db\ActiveQuery
     */
    public static function findClientEvents(int $id): ActiveQuery
    {
        return Event::find()->with('client')->select(['id', 'client_id', 'master_id', 'description', 'event_time_start'])
            ->where(
            ['client_id' => $id]
        );
    }

    /**
     * Returns a list of future records
     *
     * @param $user_id
     *
     * @return array|\common\models\Event[]|\yii\db\ActiveRecord[]
     */
    public static function findNextClientEvents($user_id)
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
    public static function findPreviousClientEvents($user_id)
    {
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
    public static function countEventTotal($masterIds)
    {
        return Event::find()->where(['>=', 'event_time_start', date('Y-m-d')])
            ->andWhere(['master_id' => $masterIds])
            ->count('client_id');
    }

    /**
     * Total number of records for each master
     *
     * @param $masterIds
     *
     * @return array
     */
    public function getTotalEventsMaster($masterIds): array
    {
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
    }


    /**
     * Return event list dataProvider
     *
     * @return \yii\data\ActiveDataProvider
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public static function getEventDataProvider($userId): ActiveDataProvider
    {
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
        $dependency   = Yii::createObject(
            [
                'class'    => 'yii\caching\DbDependency',
                'sql'      => 'SELECT MAX(event_time_start) FROM event',
                'reusable' => true
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
