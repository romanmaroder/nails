<?php


namespace common\modules\profile\models;

use common\models\Certificate;
use common\models\Profile;
use common\models\User;
use Intervention\Image\ImageManager;
use Yii;

class AddCertificate extends \yii\base\Model
{
    public $image;
    private $user;


    public function rules()
    {
        return [
            [
                ['image'],
                'file',
                'extensions'               => ['jpg'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'image'=>'Сертификат'
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
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizeCertificate']);
    }

    /**
     * Resize image if needed
     */
    public function resizeCertificate()
    {
        if ($this->image->error) {
            return;
        }
        $width  = Yii::$app->params['certificatePicture']['maxWidth'];
        $height = Yii::$app->params['certificatePicture']['maxHeight'];

        $manager = new ImageManager(['driver' => 'imagick']);

        $image = $manager->make($this->image->tempName);
        $image->fit($width,$height ,function ($constraint){$constraint->upsize();$constraint->aspectRatio();})->save
        (null, null,'jpg');

    }


    /**
     * @return bool|array
     */
    public function save()
    {
        if ($this->validate()) {

            $picture          = new Certificate();
            $picture->certificate      = Yii::$app->storage->saveUploadedFile($this->image);
            $picture->user_id     = $this->user->getId();

            return $picture->save(false);
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