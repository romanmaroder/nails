<?php

namespace common\models;

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
            [['event_time_start', 'event_time_end'], 'safe'],
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
        return Event::find()->where(['master_id' => $id])->andWhere('event_time_start >= DATE(NOW())')->orderBy(
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
        return Event::find()->where('event_time_start >= DATE(NOW())')->orderBy(['event_time_start' => SORT_ASC]);
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
        return Event::find()->select(['id', 'client_id', 'master_id', 'description', 'event_time_start'])->where(
            ['client_id' => $id]
        );
    }

    /**
     * Returns a list of future records
     * @param $user_id
     *
     * @return array|\common\models\Event[]|\yii\db\ActiveRecord[]
     */
    public static function findNextClientEvents( $user_id)
    {
        return Event::find()
            ->select('event_time_start, description')
            ->where(['client_id'=>$user_id])
            ->andWhere(['>','event_time_start',new Expression('CURDATE()')])
            ->asArray()
            ->all();
    }


    /**
     * Returns a list of previous records
     * @param $user_id
     *
     * @return array|\common\models\Event[]|\yii\db\ActiveRecord[]
     */
    public static function findPreviousClientEvents( $user_id)
    {
        return Event::find()
            ->select('event_time_start, description')
            ->where(['client_id'=>$user_id])
            ->andWhere(['<','event_time_start',new Expression('CURDATE()')])
            ->orderBy(['ABS'=>new Expression('DATEDIFF(NOW(), `event_time_start`)')])
            ->limit(2)
            ->asArray()
            ->all();
    }

    /**
     * The total number of entries in the calendar
     *
     * @return bool|int|string|null
     */
    public static function countEventTotal()
    {
        return Event::find()->where(['>=', 'event_time_start', date('Y-m-d')])
            ->count();
    }

    /**
     * Total number of records for each master
     */
    public function getTotalEventsMaster($masterIds): array
    {
        return Event::find()->select(['COUNT(client_id) AS totalEvent','master_id'])
            ->with(['master'=>function($q){$q->select(['id','username','avatar']);}])
            ->where(['master_id' => $masterIds])
            ->andWhere(['>=', 'event_time_start', date('Y-m-d')])
            ->groupBy('master_id')
            ->all();
    }
}
