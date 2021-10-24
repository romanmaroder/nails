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
        'css/site.css',
        '//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&display=swap',
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'
    ];

    public $js = [
        'js/adminlte.min.js',
        'js/yii.confirm.overrides.js'

    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
//        'hail812\adminlte3\assets\BaseAsset',
        'hail812\adminlte3\assets\PluginAsset',
    ];
}