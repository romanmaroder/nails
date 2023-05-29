<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
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

    public $totalCount;
    private const DEFAULT_BG = "img/bg/";

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
     * @return ActiveDataProvider
     */
    public function getPhotoList($id, $masterIds): ActiveDataProvider
    {
        if (in_array($id, $masterIds)) {
            $column_name = 'user_id';
        } else {
            $column_name = 'client_id';
        }
        return new ActiveDataProvider(
            [
                'query' => Photo::find()
                    ->with('user', 'master')
                    ->where([$column_name => $id])
            ]
        );
    }

    /**
     * Getting a photo for the gallery
     *
     * @param int|null $id
     * @return array
     */
    public function getPortfolio(int $id = null): array
    {
        $query = Photo::find()->where(['portfolio' => 1]);
        if ($id) {
            $query->andWhere(['user_id' => $id])->asArray();
        }
        return $query->all();
    }

    /**
     * Delete picture from user record and file system
     *
     * @return bool
     */

    public function deletePicture(): bool

    {
        if ($this->image && Yii::$app->storage->deleteFile($this->image)) {
            $this->image = null;

            return $this->save(false, ['image']);
        }

        return false;
    }


    /**
     * Getting a background picture for a card
     *
     * @return string
     */
    public static function getBackgroundCard(): string
    {
        $images = scandir(self::DEFAULT_BG);
        $arr    = [];
        foreach ($images as $image) {
            if ($image == '.' || $image == '..') {
                continue;
            }
            $arr[] = $image;
        }

        $img = rand(0, sizeof($arr) - 1);
        return $path = "/".self::DEFAULT_BG.$arr[$img];
    }

    /**
     * The total number of photos from the master
     *
     * @param $column_name
     * @param $masterIds
     *
     * @return array
     */
    public function getTotalPhotoCount($masterIds): array
    {
        return Photo::find()->select(['COUNT(client_id) AS totalCount', 'user_id'])
            ->where(['user_id' => $masterIds])
            ->andWhere(['IS NOT','client_id',NULL])
            ->groupBy('user_id')
            ->all();
    }
    
    public function getTotalPortfolioPhotoCount($masterIds): array
    {
        return Photo::find()->select(['COUNT(master_work) AS totalCount', 'user_id'])
            ->where(['user_id' => $masterIds])
            ->andWhere(['!=','portfolio',0])
            ->groupBy('user_id')
            ->all();
    }

    public function getTotalMasterPhotoCount($masterIds): array
    {
        return Photo::find()->select(['COUNT(master_work) AS totalCount', 'user_id'])
            ->where(['user_id' => $masterIds])
            ->andWhere(['!=','master_work',0])
            ->groupBy('user_id')
            ->all();
    }
}
