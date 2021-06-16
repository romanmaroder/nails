<?php

namespace common\components;

use yii\web\UploadedFile;

/**
 * Interface StorageInterface
 *
 * @package common\components
 */
interface StorageInterface
{
    public function saveUploadedFile(UploadedFile $file);

    public function getFile(string $filename);
}