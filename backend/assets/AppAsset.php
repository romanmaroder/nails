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

    ];
    public $js = [
        'js/tooltip.js',
    ];
    public $depends = [
        'common\assets\AdminLteAsset',
        'common\assets\FontAwesomeAsset',
    ];
}
