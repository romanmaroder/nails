<?php


namespace common\widgets\todoList\assets;


use yii\web\AssetBundle;

class TodoListAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/todoList/web';
    public $css = [
        'css/todo.css',
    ];
    public $js = [
        'js/todo.js',
    ];
    public $depends = [
        //'yii\web\YiiAsset',

    ];
}