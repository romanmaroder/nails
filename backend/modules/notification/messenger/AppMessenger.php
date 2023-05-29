<?php


namespace backend\modules\notification\messenger;


use backend\modules\notification\messenger\interfaces\MessengerInterface;
use backend\modules\notification\messenger\traits\CommonAdditionalMethods;

class AppMessenger implements MessengerInterface
{
use CommonAdditionalMethods;

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