<?php
namespace common\widgets\todoList;

use common\widgets\todoList\assets\TodoListAsset;
use yii\base\Widget;
class TodoList extends Widget
{

    public function run()
    {
        TodoListAsset::register($this->view);
        return $this->render('index');
    }
}