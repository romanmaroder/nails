<?php

namespace backend\modules\viber\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `viber` module
 */
class ViberController extends Controller
{
    
    public function actionWebhook () {

        $url = Yii::$app->request->absoluteUrl;

        $data = [
            "auth_token" => Yii::$app->params['viberToken'],
            "url" => $url
        ];

// print_r($data);

        $url = "https://chatapi.viber.com/pa/set_webhook";
        $jsonData = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        print_r($result);
    }

    public function actionViberbot(){

    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
