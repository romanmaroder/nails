<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $category_id
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $description
 * @property int|null $created_at
 * @property-read \yii\db\ActiveQuery $user
 * @property-read \yii\db\ActiveQuery $category
 * @property int|null $updated_at
 */
class Post extends ActiveRecord
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
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['title', 'subtitle'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'          => 'ID',
            'user_id'     => 'Автор',
            'category_id' => 'Категория',
            'title'       => 'Заголовок',
            'subtitle'    => 'Подзаголовок',
            'description' => 'Текст статьи',
            'status'      => 'Опубликовано',
            'created_at'  => 'Дата',
            'updated_at'  => 'Дата обновления',
        ];
    }

    /**
     * We save data to a table [[post_category]]
     *
     * @param  bool  $insert
     * @param  array  $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $postCategory              = new PostCategory();
            $postCategory->post_id     = $this->id;
            $postCategory->category_id = $this->category_id;
            $postCategory->save();

            $postImg = PostImage::find()
                ->andWhere(['or',['post_id' => null],['post_id'=>$this->id]])
                ->all();
            foreach ($postImg as $item) {
                $item->post_id = $this->id;
                $item->update(false);
            }
        } else {
            $postCategory              = PostCategory::find()->where(['post_id' => $this->id])->one();
            $postCategory->post_id     = $this->id;
            $postCategory->category_id = $this->category_id;
            $postCategory->save();

            $postImg = PostImage::find()
                ->andWhere(['or',['post_id' => null],['post_id'=>$this->id]])
                ->all();
            foreach ($postImg as $item) {
                $item->post_id = $this->id;
                $item->update(false);
            }

        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Relations to table [[user]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Relations to table [[category]]
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Publishing an article
     *
     * @param $id
     *
     * @return bool
     */
    public function toPublish($id): bool
    {
        $post = Post::find()->select('id,status')->where(['id' => $id])->one();

        if ($post->status == 0) {
            $post->status = 1;
            $post->save();
            return true;
        } else {
            $post->status = 0;
            $post->save();
            return false;
        }
    }

    /**
     * Returns the specified number of articles
     *
     * @param $max
     *
     * @return array
     */
    public static function getPostList($max): array
    {
        return Post::find()->where(['status' => 1])->orderBy('RAND()')->asArray()->limit($max)->all();
    }

    public static function getAllPostList(): array
    {
        return Post::find()->where(['status' => 1])->asArray()->all();
    }


}
