<?php


namespace common\models;


use Yii;
use yii\base\Model;

class Setting extends Model
{
    public $checkbox = 0;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['checkbox'], 'safe'],
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


    public function setCookies(){

        $cookies = Yii::$app->response->cookies;

        $cookies->add(new \yii\web\Cookie([
            'name' => 'theme',
            'value' => 'dark-mode',
            'expire' =>Yii::$app->getFormatter()->asTimestamp(date('Y-m-d H:i:s')) + 86400 * 365,
            #'expire' =>time()+(60*60*24*30),
        ]));
    }

    public function deleteCookies(){

        $cookies = Yii::$app->response->cookies;
        $cookies->remove('theme');


    }
}