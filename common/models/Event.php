<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
            [['client_id'], 'integer','message' => 'Выберите клиента'],
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
            'master_id'          => 'Мастер',
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

    public static function findMasterEvents($id): ActiveQuery
    {
        return Event::find()->where(['master_id' => $id])->andWhere('event_time_start >= DATE(NOW())');
    }

    public static function findClientEvents($id): ActiveQuery
    {
        return Event::find()->select(['id','client_id','master_id','description','event_time_start'])->where(['client_id' =>$id]);
    }


    public static function countEventTotal()
    {
        return Event::find()->where(['>=','event_time_start',date('Y-m-d')])->groupBy('event_time_start')
            ->count();
    }
}
