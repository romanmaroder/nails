<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "photo".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $portfolio
 * @property int|null $client_id
 * @property-read \yii\db\ActiveQuery $master
 * @property-read \yii\db\ActiveQuery $user
 * @property int|null $master_work
 */
class Photo extends ActiveRecord
{

    public function behaviors(): array
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
    public static function tableName(): string
    {
        return 'photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'portfolio', 'client_id', 'master_work'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'user_id'     => 'User ID',
            'portfolio'   => 'Portfolio',
            'client_id'   => 'Client ID',
            'master_work' => 'Master Work',
        ];
    }


    /**
     * Relationship with [[User]] table
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'client_id']);
    }

    /**
     * Relationship with [[User]] table
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaster(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Getting a list of photos
     *
     * @param $id
     * @param $masterIds
     *
     * @return array|\common\models\Photo[]|\yii\db\ActiveRecord[]
     */
    public function getPhotoList($id, $masterIds): array
    {
        if (in_array($id, $masterIds)) {
            $column_name = 'user_id';
        } else {
            $column_name = 'client_id';
        }
        return Photo::find()
            ->with('user', 'master')
            ->where([$column_name => $id])
            ->asArray()
            ->all();
    }

    /**
     * Getting a photo for the gallery
     *
     * @return array
     */
    public function getPortfolio(): array
    {
        return Photo::find()->where(['portfolio' => 1])->all();
    }

}
