<?php


namespace common\models;


use Yii;
use yii\base\Model;

class Setting extends Model
{
    public $themeColor;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['themeColor'], 'safe'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'themeColor'=>'Темная тема'
        ];
    }


    public function SetCookies(){
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'theme',
            'value' => 'dark',
        ]));
    }
}