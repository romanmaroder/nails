<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "expenseslist".
 *
 * @property int $id
 * @property int|null $expenses_id
 * @property int|null $price
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Expenses $expenses
 */
class Expenseslist extends \yii\db\ActiveRecord
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
        return 'expenseslist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expenses_id', 'price'], 'required'],
            [['expenses_id', 'price', 'created_at', 'updated_at'], 'integer'],
            [['expenses_id'], 'exist', 'skipOnError' => true, 'targetClass' => Expenses::class, 'targetAttribute' => ['expenses_id' => 'id']],
        ];

    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'expenses_id' => 'Категория расходов',
            'price' => 'Цена',
            'created_at' => 'Дата',
            'updated_at' => 'Дата редактирования',
        ];
    }

    /**
     * Gets query for [[Expenses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpenses(): \yii\db\ActiveQuery
    {
        return $this->hasOne(Expenses::class, ['id' => 'expenses_id']);
    }


    /**
     * @param $dataProvider
     * @return array
     */
    public static function getlabelsCharts($dataProvider): array
    {
        $labels = [];

        foreach ($dataProvider as $model) {

                if (!in_array($model->expenses->title, $labels)) {
                    $labels[] = $model->expenses->title;
                }
        }


        return $labels;
    }

    public static function getDataCharts($dataProvider): array
    {

        $amount = [];
        foreach ($dataProvider->models as $model) {

                if (!in_array($model->price, $amount)) {
                    $amount[$model->expenses->id] += $model->price;
                }
        }

        return array_values($amount);

    }


}
