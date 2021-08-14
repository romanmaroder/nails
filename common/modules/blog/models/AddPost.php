<?php
namespace common\modules\blog\models;

use common\models\Post;
use common\models\User;
use Yii;
use yii\base\Model;
use Intervention\Image\ImageManager;

/**
 *
 * @property-read int $maxFileSize
 */
class AddPost extends Model
{
    public $id;
    public $picture;
    public $user_id;
    public $category_id;
    public $status;
    public $description;
    public $title;
    public $subtitle;
    public $slug;
    private $user;

    public function rules()
    {
        return [
            [
                ['picture'],
                'file',
                'extensions'               => ['jpg','png'],
                'checkExtensionByMimeType' => true,
                'maxSize'                  => $this->getMaxFileSize(),
            ],
            [['picture'],'required','message' => 'Выберите превью'],
            [['category_id'],'required','message' => 'Выберите категорию'],
            [['description'], 'string'],
            [['title', 'subtitle'], 'string', 'max' => 255],
            [['title'],'required','message' => 'Придумайте заголовок'],
            [['subtitle'],'required','message' => 'Придумайте подзаголовок'],
            [['user_id', 'category_id', 'status'], 'integer'],
            [['created_at', 'updated_at','slug'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'user_id'     => 'Автор',
            'category_id' => 'Категория',
            'title'       => 'Заголовок',
            'subtitle'    => 'Подзаголовок',
            'description' => 'Текст статьи',
            'status'      => 'Опубликовано',
            'picture'     => 'Превью статьи',
            'created_at'  => 'Дата',
            'updated_at'  => 'Дата обновления',
        ];
    }

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
    public function save()
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