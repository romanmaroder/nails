<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
    public $picture;

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
            'slug' => [
                'class'                => 'Zelenin\yii\behaviors\Slug',
                'slugAttribute'        => 'slug',
                'attribute'            => 'title',
                // optional params
                'ensureUnique'         => true,
                'replacement'          => '-',
                'lowercase'            => true,
                'immutable'            => true,
                // If intl extension is enabled, see http://userguide.icu-project.org/transforms/general.
                'transliterateOptions' => 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;'
            ]
        ];
    }

    public function rules()
    {
        return [
            [
                ['picture'],
                'file',
                'extensions'               => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize'                  => $this->getMaxFileSize(),
            ],
            [['picture'], 'required', 'message' => 'Выберите превью'],
            [['category_id'], 'required', 'message' => 'Выберите категорию'],
            [['description'], 'string'],
            [['title', 'subtitle'], 'string', 'max' => 255],
            [['title'], 'required', 'message' => 'Придумайте заголовок'],
            [['subtitle'], 'required', 'message' => 'Придумайте подзаголовок'],
            [['user_id', 'category_id', 'status'], 'integer', 'message' => 'Выберите {attribute}'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'post';
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
            'preview'     => 'Превью статьи',
            'picture'     => 'Превью статьи',
            'created_at'  => 'Дата',
            'updated_at'  => 'Дата обновления',
        ];
    }

    /**
     * We save data to a table [[post_category]]
     *
     * @param  bool  $insert
     * @param  array  $changedAttributes
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $postCategory = new PostCategory();
        } else {
            $postCategory = PostCategory::find()->where(['post_id' => $this->id])->one();
        }
        $postCategory->post_id     = $this->id;
        $postCategory->category_id = $this->category_id;
        $postCategory->save();
        $postImg = PostImage::find()
            ->andWhere(['or', ['post_id' => null], ['post_id' => $this->id]])
            ->all();
        foreach ($postImg as $item) {
            $item->post_id = $this->id;
            $item->update(false);
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
            $post->save(false);
            return true;
        } else {
            $post->status = 0;
            $post->save(false);
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
        return Post::find()->with('user', 'category')->where(['status' => 1])->orderBy('RAND()')->asArray()->limit($max)
            ->all();
    }

    /*public static function getAllPostList(): array
    {
        return Post::find()->with('user', 'category')->where(['status' => 1])->asArray()->all();
    }*/

    public static function getAuthorPostList(): array
    {
        $authorList = Post::find()->with('user')->select('user_id')->where(['status' => 1])->asArray()->all();

        return  ArrayHelper::map($authorList, 'user_id', 'user.username');
    }

    /**
     * Get a preview of the article
     *
     * @param $id
     *
     * @return array|\common\models\Post|\yii\db\ActiveRecord|null
     */
    public static function getPreview($id)
    {
        return Post::find()->select('preview')->where(['id' => $id])->one();
    }

    /**
     * Maximum size of the uploaded file
     *
     * @return int
     */
    private function getMaxFileSize(): int
    {
        return Yii::$app->params['maxFileSize'];
    }
}
