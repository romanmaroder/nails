<?php


namespace common\widgets\xmas\assets;


use yii\web\AssetBundle;

class XmasAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/xmas/web';
    public $css = [
        //'css/matterhorn_rus_by_me.css',
        'css/main.css',
    ];
    public $js = [
        '',
    ];
    public $depends = [
//        'yii\web\YiiAsset',
    ];
}