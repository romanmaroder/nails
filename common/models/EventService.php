<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "event_service".
 *
 * @property int $event_id
 * @property int $service_id
 *
 * @property Event $event
 * @property Service $service
 * @property int $id [int(11)]
 */
class EventService extends ActiveRecord
{
    public int $amount;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'event_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id', 'service_id'], 'required'],
            [['event_id', 'service_id'], 'integer'],
            [['event_id', 'service_id'], 'unique', 'targetAttribute' => ['event_id', 'service_id']],
            [
                ['event_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Event::class,
                'targetAttribute' => ['event_id' => 'id']
            ],
            [
                ['service_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Service::class,
                'targetAttribute' => ['service_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'event_id'   => 'Event ID',
            'service_id' => 'Service ID',
            'amount'     => 'Итого'
        ];
    }

    /**
     * Gets query for [[Event]].
     *
     * @return ActiveQuery
     */
    public function getEvent(): ActiveQuery
    {
        return $this->hasOne(Event::class, ['id' => 'event_id']);
    }

    /**
     * Gets query for [[Service]].
     *
     * @return ActiveQuery
     */
    public function getService(): ActiveQuery
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }


}
