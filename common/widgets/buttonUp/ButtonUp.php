<?php
namespace common\widgets\buttonUp;
use common\widgets\buttonUp\assets\ButtonUpAsset;
use yii\base\Widget;
class ButtonUp extends Widget
{
    public function run() {
        //Подключаем свой файл Asset
        ButtonUpAsset::register($this->view);
        return $this->render('buttonup',[
        ]);
    }
}