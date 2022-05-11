<?php

namespace backend\modules\telegram\api;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;

class TelegramBot extends Api
{
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
