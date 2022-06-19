<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "service".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $cost
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Service extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cost', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

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
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'name'       => 'Услуга',
            'cost'       => 'Цена',
            'created_at' => 'Дата',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * Return all services
     * @return array
     */
    public static function getServiceList(): array
    {
        $services = self::find()->asArray()->orderBy(['name'=>SORT_ASC])->all();

        return ArrayHelper::map($services,'id','name');
    }
}
