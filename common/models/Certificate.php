<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "certificate".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $certificate
 *
 * @property User $user
 */
class Certificate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'certificate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['certificate'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'certificate' => 'Certificate',
        ];
    }

    /**
     * Delete picture from user record and file system
     *
     * @return bool
     */

    public function deletePicture(): bool

    {
        if ($this->certificate && Yii::$app->storage->deleteFile($this->certificate)) {
            $this->certificate = null;

            return $this->save(false, ['certificate']);
        }

        return false;
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser(): \yii\db\ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public  function getCertificates($id): ActiveDataProvider
    {
        return new ActiveDataProvider(
            [
                'query' => Certificate::find()
                    ->with('user')
                    ->where(['user_id' => $id])

            ]
        );
    }
}
