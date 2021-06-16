<?php
//namespace hail812\adminlte3\assets;
namespace common\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    //public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/adminlte.min.css',
        'css/site.css'
    ];

    public $js = [
        'js/adminlte.min.js'
    ];

    public $depends = [
        'hail812\adminlte3\assets\BaseAsset',
        'hail812\adminlte3\assets\PluginAsset',
    ];
}