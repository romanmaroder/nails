<?php


namespace common\widgets\buttonUp\assets;


use yii\web\AssetBundle;

class ButtonUpAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/buttonUp/web';
    public $css = [
        'css/button_up.css',
    ];
    public $js = [
        'js/button_up.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}