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
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css',
        'css/site.css',
    ];
    public $js = [
        'js/tooltip.js',
        'js/main.js',
    ];
    public $depends = [
        'common\assets\AdminLteAsset',
    ];
}
