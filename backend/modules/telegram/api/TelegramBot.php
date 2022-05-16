<?php

namespace backend\modules\telegram\api;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;
use Yii;

class TelegramBot extends Api
{

    public function __construct($token = null, $async = false, $http_client_handler = null)
    {
        $token = Yii::$app->params['telegramToken'];
        parent::__construct($token, $async, $http_client_handler);
    }

    /**
     *  Method for sending responses to callback requests sent from built-in keypads
     * @param array $params
     *
     * @return Message
     */
    public function answerCallbackQuery(array $params): Message
    {
        $response = $this->post('answerCallbackQuery', $params);
        return new Message($response->getDecodedBody());
    }

    /**
     *
     * @param $method
     * @param array $params
     *
     * @return Message
     */
    public function customSendRequest($method, array $params = []): Message
    {
        $response = $this->post($method, $params);
        return new Message($response->getDecodedBody());
    }



}
