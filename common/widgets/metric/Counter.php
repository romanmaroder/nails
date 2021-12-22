<?php


namespace common\widgets\metric;


use common\widgets\metric\assets\MetricAsset;
use yii\base\Widget;

class Counter extends Widget
{
    public function run()
    {
        //Подключаем свой файл Asset
        MetricAsset::register($this->view);
        $this->view->registerMetaTag(['name' => 'yandex-verification', 'content' => '137c90eb652e5c9b']);
        //return $this->render('_counters');
    }

}