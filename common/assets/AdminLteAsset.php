<?php
namespace common\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/';

    public $css = [
        'dist/css/adminlte.min.css',
        'plugins/fontawesome-free/css/all.min.css',

        '//fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,700;1,100;1,300;1,400;1,700&display=swap',
    ];

    public $js = [
        'dist/js/adminlte.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
        'hail812\adminlte3\assets\PluginAsset',
    ];
}