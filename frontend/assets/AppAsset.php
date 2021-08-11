<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        '//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&display=swap',
    ];
    public $js = [
        'js/tooltip.js',
        'js/main.js',
    ];
    public $depends = [
        'common\assets\FontAwesomeAsset',
        'common\assets\AdminLteAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
