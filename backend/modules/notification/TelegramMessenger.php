<?php


namespace backend\modules\notification;


use backend\modules\telegram\api\TelegramBot;
use backend\modules\telegram\models\Telegram;
use Telegram\Bot\Objects\Message;
use Yii;

class TelegramMessenger extends AbstractMessenger
{
    private TelegramBot $messenger;
    private string      $recipient;
    protected string    $text;

    public function __construct()
    {
        $this->messenger = new TelegramBot();
    }

    private function setReceiver($recipient): string
    {
        $id = new Telegram();
        if ($id->findById($recipient)) {
            $this->recipient = $id->findById($recipient);
        }
        return $this->recipient;
    }

    public function send(array $params): Message
    {
        return $this->messenger->sendMessage(
            [
                'chat_id' => $this->setReceiver($params['id']),
                'text'    => Yii::$app->smsSender->messageText($params['event_time_start'])
            ]
        );
    }

}