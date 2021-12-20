<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/yii.confirm.overrides.js',
        'js/tooltip.js',
        'js/main.js'
    ];
    public $depends = [
        'common\assets\AdminLteAsset',
        'common\assets\FontAwesomeAsset',
    ];
}
