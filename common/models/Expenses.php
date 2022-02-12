<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "expenses".
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Expenses extends \yii\db\ActiveRecord
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
        return 'expenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'created_at', 'updated_at'], 'integer'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Расходы',
            'created_at' => 'Дата',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return array
     */
    public static function getTitle(): array
    {
        $expenses =  Expenses::find()->all();

        return ArrayHelper::map($expenses,'id','title');
    }


}
