<?php
namespace common\modules\blog\models;

use common\models\Post;
use common\models\User;
use Yii;
use Intervention\Image\ImageManager;


/**
 *
 * @property-read int $maxFileSize
 */
class AddPost extends Post
{

    private $user;


    /**
     *@param  User  $user
     * @param  array  $config
     */
    public function __construct(User $user,array $config = [])
    {
        parent::__construct($config);
        $this->user = $user;
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePreview']);
    }

    /**
     * Resize image if needed
     */
    public function resizePreview()
    {
        if ($this->picture->error) {
            return;
        }
        $height = Yii::$app->params['postPreview']['maxHeight'];

        $manager = new ImageManager(['driver' => 'imagick']);

        $image = $manager->make($this->picture->tempName);
        $image->heighten( $height,function ($constraint) {
            $constraint->upsize();
            $constraint->aspectRatio();
        })->save
        (
            null,
            null,
            'jpg'
        );
    }


    /**
     * @return bool|array
     */
    public function saved()
    {
        if ($this->validate()) {
            $post              = new Post();
            $post->preview     = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->user_id     = $this->user->getId();
            $post->category_id = $this->category_id;
            $post->title       = $this->title;
            $post->subtitle    = $this->subtitle;
            $post->description = $this->description;
            $post->status      = $this->status;
            $post->slug      = $this->slug;
            return $post->save(false);
        }
        return $this->getErrors();
    }

}