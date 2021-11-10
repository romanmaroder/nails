<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string|null $category_name
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['category_name'],
                'string',
                'max' => 255
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'category_name' => 'Название категории',
        ];
    }

    /**
     * Get all post category
     * @return array
     * @throws \Throwable
     */
    public static function getCategoryList(): array
    {
        $categories = Category::getDb()->cache(
            function () {
                return Category::find()->asArray()->all();
            },
            3600
        );

        return ArrayHelper::map($categories, 'id', 'category_name');
    }


    /**
     * Get categories of published posts with status = 1
     * @return array
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public static function getCategoryPostList(): array
    {
        $dependency   = Yii::createObject(
            [
                'class' => 'yii\caching\DbDependency',
                'sql'   => 'SELECT MAX(updated_at) FROM post ',
                'reusable'=>true
            ]
        );
            $categoriesIds =Post::find()->select('category_id')->where(['status'=>1])->asArray()->distinct();

        $categories = Category::getDb()->cache(function () use($categoriesIds){
            return Category::find()->where(['id' => $categoriesIds])->asArray()->all();
        }, null,$dependency);

        return ArrayHelper::map($categories, 'id', 'category_name');
    }

}
