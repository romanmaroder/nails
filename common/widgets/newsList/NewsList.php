<?php


namespace common\widgets\newsList;


use common\models\Post;
use Yii;
use yii\base\Widget;

class NewsList extends Widget
{
    public $showLimit = null;

    public function run()
    {
        $max = Yii::$app->params['maxLimitNews'];
        if ($this->showLimit) {
            $max=$this->showLimit;
        }
        $list = Post::getPostList($max);
        return $this->render('index',['list'=>$list]);
    }
}