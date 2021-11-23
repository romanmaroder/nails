<?php

namespace backend\modules\viber\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "viber".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $viber_user_id
 * @property string|null $primary_device_os
 * @property int|null $api_version
 * @property string|null $device_type
 *
 * @property User $user
 */
class Viber extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'viber';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'api_version'], 'integer'],
            [['name', 'viber_user_id', 'primary_device_os', 'device_type'], 'string', 'max' => 255],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
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
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'viber_user_id' => 'Viber User ID',
            'primary_device_os' => 'Primary Device Os',
            'api_version' => 'Api Version',
            'device_type' => 'Device Type',
        ];
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


    public static function start(
        string $viber_user_id,
        string $name,
        int $users_id,
        string $primary_device_os = null,
        int $api_version = null,
        string $device_type = null
    ) {
        $model = new Viber();

        if ($viber_user_id == self::getOldId($viber_user_id)) {
            return false;
        } else {
            $model->viber_user_id = $viber_user_id;
            $model->name = $name;
            $model->user_id = $users_id;
            $model->primary_device_os = $primary_device_os;
            $model->api_version = $api_version;
            $model->device_type = $device_type;
            $model->save();
        }
        return true;
    }

    public static function getOldId($viber_user_id)
    {
        $oldId =  Viber::find()->where(['viber_user_id' => $viber_user_id ])->one();
        return $oldId->viber_user_id;
    }

    public static function getUserId($viber_user_id)
    {
        return static::find()->select('user_id')->where(['viber_user_id' => $viber_user_id]);
    }

}
