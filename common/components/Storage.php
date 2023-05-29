<?php


namespace common\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * Class Storage
 *
 * @property-read string $storagePath
 */
class Storage extends Component implements StorageInterface
{
    private ?string $fileName;

    /**
     * Save given UploadedFile instance to disk
     *
     * @param  \yii\web\UploadedFile  $file
     *
     * @return string|null
     * @throws \yii\base\Exception
     */
    public function saveUploadedFile(UploadedFile $file): ?string
    {
        $path = $this->preparePath($file);

        if ($path && $file->saveAs($path)) {
            return $this->fileName;
        }
        return false;
    }

    /**
     * Prepare path to save uploaded file
     *
     * @param  \yii\web\UploadedFile  $file
     *
     * @return string|null
     * @throws \yii\base\Exception
     */
    protected function preparePath(UploadedFile $file): ?string
    {
        $this->fileName = $this->getFileName($file);
        // 0c/f6/979dsf65df46ewf56.jpg

        $path = $this->getStoragePath().$this->fileName;
        // /var/www/project/frontend/web/uploads/0c/s9/fderf54er56532.jpg

        $path = FileHelper::normalizePath($path);
        if (FileHelper::createDirectory(dirname($path))) {
            return $path;
        }
        return false;
    }

    /**
     * @param  string  $filename
     *
     * @return string
     */
    public function getFile(string $filename): string
    {
        return Yii::$app->params['storageUri'].$filename;
    }

    /**
     * @param  \yii\web\UploadedFile  $file
     *
     * @return string
     */
    protected function getFileName(UploadedFile $file): string
    {
        // $file->tempName - /tmp/dsfasda
        $hash = sha1_file($file->tempName);
        // 0ccds36ewf2dfd1s32f13er2fewf
        $name = substr_replace($hash, '/', 2, 0);
        $name = substr_replace($name, '/', 5, 0);
        // 0c/s5/csd46dsfaf48e9f466
        return $name.'.'.$file->extension;
        //  0c/s5/csd46dsfaf48e9f466.jpg
    }

    /**
     * @return string
     */
    protected function getStoragePath(): string
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }

    /**
     * @param  string  $filename
     *
     * @return bool
     */

    public function deleteFile(string $filename): bool
    {
        $file = $this->getStoragePath().$filename;


        if (file_exists($file)) {
            // Если файл существует, удаляем
            return unlink($file);
        }

        // Файла нет - хорошо. И удалять не нужно

        return true;
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function checkFileExist(string $filename):bool {

        $file = $this->getStoragePath().$filename;

        if (file_exists($file)) {
            return true;
        }
        return false;
    }
}