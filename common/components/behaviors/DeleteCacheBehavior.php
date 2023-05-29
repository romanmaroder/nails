<?php
namespace common\components\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

class DeleteCacheBehavior extends Behavior {

    public $cache_key;
    public $actions;

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION  => 'deleteCache',
        ];
    }

    public function deleteCache()
    {
        $action = Yii::$app->controller->action->id; //название текущего действия
        if(array_search($action, $this->actions)=== false) return;

        Foreach ($this->cache_key as $id){
            Yii::$app->cache->delete($id);
        }
    }
}