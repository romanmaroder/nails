<?php


namespace backend\modules\notification;


use backend\modules\telegram\api\TelegramBot;
use backend\modules\viber\api\ViberBot;
use Yii;

class AppMessenger implements MessengerInterface
{

    protected MessengerInterface $messenger;
    protected string             $text;

    public function toTelegram(): AppMessenger
    {
        $this->messenger = new TelegramMessenger();
        return $this;
    }

    public function toViber(): AppMessenger
    {
        $this->messenger = new ViberMessenger();
        return $this;
    }

    public function send($params)
    {
        return $this->messenger->send($params);
    }
}