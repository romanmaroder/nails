<?php


namespace common\modules\profile\models;

use common\models\User;
use Intervention\Image\ImageManager;
use Yii;
use yii\base\Model;

class AvatarForm extends Model
{

    public $avatar;
    private $user;

    public function rules()
    {
        return [
            [
                ['avatar'],
                'file',
                'extensions'               => ['jpg'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
            ],
        ];
    }

    /**
     * @param  User  $user
     * @param  array  $config
     */
    public function __construct(User $user, array $config = [])
    {
        parent::__construct($config);
        $this->user = $user;
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizeAvatar']);
    }

    /**
     * Resize image if needed
     */
    public function resizeAvatar()
    {
        if ($this->avatar->error) {
            return;
        }
        $width  = Yii::$app->params['avatarPicture']['maxWidth'];
        $height = Yii::$app->params['avatarPicture']['maxHeight'];

        $manager = new ImageManager(['driver' => 'imagick']);

        $image = $manager->make($this->avatar->tempName);
        $image->fit($width,$height ,function ($constraint){$constraint->upsize();})->save(null, null,'jpg');

    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if ($this->validate()) {
            $this->user->avatar = Yii::$app->storage->saveUploadedFile($this->avatar);

            if ($this->user->save(false, ['avatar'])) {
                return true;
            }
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



}
