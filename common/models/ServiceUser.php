<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "service_user".
 *
 * @property int $id
 * @property int|null $service_id
 * @property int|null $user_id
 * @property int|null $rate
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Service $service
 * @property User $user
 */
class ServiceUser extends \yii\db\ActiveRecord
{

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
        return 'service_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'user_id'], 'required'],
            [['service_id', 'user_id'], 'integer'],
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
            [
                ['rate'],
                'number',
                'min'     => 0,
                'max'     => 100,
                'message' => '{attribute} не может быть меньше 0 и  больше 100'
            ],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'service_id' => 'Услуга',
            'user_id'    => 'Мастер',
            'rate'       => 'Ставка',
            'created_at' => 'Дата',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * Gets query for [[Service]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }


    public static function getUserServices(){

        $services = self::find()->asArray()->all();
        return ArrayHelper::map($services,'service_id','service_id','user_id');
    }
}
