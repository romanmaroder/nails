<?php

namespace backend\modules\telegram\models;

use common\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "telegram".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $username
 * @property int|null $chat_id
 * @property string|null $name
 *
 * @property User $user
 */
class Telegram extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telegram';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'chat_id'], 'integer'],
            [['username', 'name'], 'string', 'max' => 255],
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
            'id'       => 'ID',
            'user_id'  => 'User ID',
            'username' => 'Username',
            'chat_id'  => 'Chat ID',
            'name'     => 'Name',
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

    public static function start($chat_id, $name, $username, $users_id, $old_id)
    {
        $model = new Telegram();

        if ($chat_id == $old_id) {
            return false;
        } else {
            $model->chat_id  = $chat_id;
            $model->name     = $name;
            $model->username = $username;
            $model->user_id  = $users_id;
            $model->save();
        }
        return true;
    }

    public static function getOldId($chat_id)
    {
        return Telegram::findOne(['chat_id' => $chat_id]);
    }

    public static function getUserId($chat_id)
    {
        return self::find()->select('user_id')->where(['chat_id' => $chat_id]);
    }

    public function findById(int $id)
    {
        $id =  self::find()->where(['user_id' => $id])->asArray()->one();

        return $id['chat_id'];
    }
}
