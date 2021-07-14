<?php


namespace common\modules\profile\models;

use Intervention\Image\ImageManager;
use Yii;
use yii\base\Model;
use common\models\Photo;
use common\models\User;
use yii\helpers\Html;

/**
 *
 * @property-read int $maxFileSize
 */
class AddPhotoForm extends Model
{

    public $picture;
    public $client;
    public $client_check;
    public $master_work;
    public $portfolio;
    private $user;



    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [
                ['picture'],
                'file',
                'skipOnEmpty'              => false,
                'extensions'               => ['jpg', 'png', 'jpeg'],
                'checkExtensionByMimeType' => true,
                'maxSize'                  => $this->getMaxFileSize()
            ],
            [['master_work', 'portfolio', 'client_check'], 'integer'],
            [['client','created_at', 'updated_at'], 'safe'],
            [
                ['client_check'],
                'required',
                'when'       => function ($model) {
                    return ($model->client_check);
                },
                'whenClient' => 'function(attribute,value){
                               if($("#'. Html::getInputId($this, 'client_check').'").is(":checked")){
                                  $("#'. Html::getInputId($this, 'client').'").next(".select2-container").css({"display":"block"});
                                  $("label[for='. Html::getInputId($this, 'client').']").removeClass("d-none");
                                 return true;
                               }else{
                                  $("#'. Html::getInputId($this, 'client').'").next(".select2-container").css({"display":"none"});
                                    $("label[for='. Html::getInputId($this, 'client').']").addClass("d-none");
                                  return true;
                               }
      }'
            ]
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'picture'      => 'Фото',
            'client_check' => 'Клиент',
            'client'       => 'Выберите клиента',
            'portfolio'    => 'Портфолио',
            'master_work'  => 'Работа мастера',
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
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePhoto']);
    }

    /**
     * Resize image if needed
     */
    public function resizePhoto()
    {
        if ($this->picture->error) {
            return;
        }

        $width  = Yii::$app->params['photoPicture']['maxWidth'];
        $height = Yii::$app->params['photoPicture']['maxHeight'];

        $manager = new ImageManager(['driver' => 'imagick']);

        $image = $manager->make($this->picture->tempName);
        $image->fit($width,$height ,function ($constraint){$constraint->upsize();$constraint->aspectRatio();})->save
        (null, null,'jpg');

        /*$image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
           $constraint->upsize();
        })->save
        (null, null,'jpg');*/

    }

    /**
     * @return
     */
    public function save()
    {
        if ($this->validate()) {
            $photo              = new Photo();
            $photo->image       = Yii::$app->storage->saveUploadedFile($this->picture);
            $photo->user_id     = $this->user->getId();
            $photo->client_id   = $this->client;
            $photo->master_work = $this->master_work;
            $photo->portfolio   = $this->portfolio;
            return $photo->save(false);
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