<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property string|null $user_id
 * @property string|null $education
 * @property string|null $notes
 * @property int|null $skill
 * @property int|null $photo_id
 * @property-read \yii\db\ActiveQuery $user
 * @property int|null $certificates_id
 */
class Profile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [['education', 'notes', 'skill'], 'string'],
            ['color', 'safe','education', 'notes', 'skill'],
            [['user_id','rate', 'certificate_id'], 'integer'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'             => 'ID',
            'user_id'        => 'Пользователь',
            'rate'           => 'Процент',
            'color'          => 'Цвет',
            'education'      => 'Образование',
            'notes'          => 'Обо мне',
            'skill'          => 'Навыки',
            'certificate_id' => 'Сертификаты',
        ];
    }

    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getUserProfileInfo($userId)
    {
        return Profile::find()
            #->select(['education','notes','skill'])
            ->where(['user_id' => $userId])
            ->one();
    }
}
