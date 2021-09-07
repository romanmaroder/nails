<?php
namespace common\widgets\buttonUp;
use yii\base\Widget;
use frontend\assets\ButtonUpAsset;
class ButtonUp extends Widget
{
    public function run() {
        //Подключаем свой файл Asset
        ButtonUpAsset::register($this->view);
        return $this->render('buttonup',[
        ]);
    }
}