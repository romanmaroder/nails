<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "archive".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $service_id
 * @property int|null $amount
 * @property string|null $date
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Service $service
 * @property User $user
 */
class Archive extends ActiveRecord
{
    public $profit;

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
    public static function tableName()
    {
        return 'archive';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'service_id', 'amount', 'salary', 'created_at', 'updated_at'], 'integer'],
            [['profit'], 'safe'],
            [['date'], 'string', 'max' => 255],
            [
                ['service_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Service::class,
                'targetAttribute' => ['service_id' => 'id']
            ],
            [
                ['user_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'Мастер',
            'service_id' => 'Услуга',
            'amount'     => 'Итого',
            'salary'     => 'Зарплата',
            'profit'     => 'Чистая',
            'date'       => 'Дата',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
