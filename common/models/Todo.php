<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "todo".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $title
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property User $user
 */
class Todo extends ActiveRecord
{


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
    public static function tableName()
    {
        return 'todo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [
                ['user_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::class,
                'targetAttribute' =>
                    ['user_id' => 'id']
            ],
            [['created_at', 'updated_at', 'status'], 'safe']
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
            'title' => 'Заметка',
            'status' => 'Статус',
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


    public static function getEventsTodo($user_id)
    {
        /*return new ActiveDataProvider(
            [
                'query' => Todo::find()->where(['user_id' => $user_id])
            ]
        );*/
        return  Todo::find()->where(['user_id' => $user_id])->asArray()->all();
    }

}
