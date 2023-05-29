<?php

namespace common\models;

use Intervention\Image\ImageManager;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "post_image".
 *
 * @property int $id
 * @property int|null $post_id
 * @property string|null $image
 * @property Post $post
 */
class PostImage extends ActiveRecord
{

    public $picture;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id'], 'integer'],
            [
                ['image'],
                'file',
                'extensions' => ['png', 'jpg'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),

            ],
            [
                ['post_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Post::class,
                'targetAttribute' => ['post_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'image' => 'Post Image',
        ];
    }


    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizeImagePost']);
    }

    /**
     * Resize image if needed
     */
    public function resizeImagePost()
    {
        if ($this->picture->error) {
            return;
        }
        $width  = Yii::$app->params['postPicture']['maxWidth'];
        $height = Yii::$app->params['postPicture']['maxHeight'];

        $manager = new ImageManager(['driver' => 'imagick']);

        $image = $manager->make($this->picture->tempName);
        $image->widen(
            $width,
            function ($constraint) {
                $constraint->upsize();
            }
        )->save(null, null, 'jpg');
    }


    /**
     * Uploading an image to a directory
     * @return bool
     */
    public function upload(): bool
    {
        $this->image = $this->picture;


        if ($this->validate()) {
            $this->image = Yii::$app->storage->saveUploadedFile($this->picture);
            if ($this->save(false, ['image'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Removing a pictureture from the directory
     *
     */
    public function delete(): bool
    {
        $image = str_replace(
            Yii::$app->request->getHostInfo() . Yii::getAlias(Yii::$app->params['storageUri']),
            '',
            $this->picture
        );

        if (Yii::$app->storage->deleteFile($image)) {
            PostImage::deleteAll(['image' => $image]);
            return true;
        }
        return false;
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

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }


    /**
     * List of all ids
     * @return array
     */
    public function getPostImageIds(): array
    {
        return PostImage::find()->where(['post_id' => null])->all();
    }
}