<?php


namespace backend\modules\notification;


use backend\modules\viber\api\ViberBot;
use backend\modules\viber\models\Viber;
use Viber\Api\Keyboard;
use Viber\Api\Keyboard\Button;
use Viber\Api\Message\Text;
use Viber\Api\Response;
use Viber\Api\Sender;
use Yii;

class ViberMessenger extends AbstractMessenger
{
    private ViberBot $messenger;
    private string   $recipient;
    protected string $text;

    public function __construct()
    {
        $this->messenger = new ViberBot();
    }

    private function setReceiver($recipient): string
    {
        $id = new Viber();
        if ($id->findById($recipient)) {
            $this->recipient = $id->findById($recipient);
        }
        return $this->recipient;
    }


    public function send(array $params): Response
    {
        return $this->messenger
            ->getClient()
            ->sendMessage((new Text())
                              ->setSender(
                                  new Sender(
                                      [
                                          'name'   => Yii::$app->params['viber']['viberBotName'],
                                          'avatar' => Yii::$app->params['viber']['viberBotAvatar'],
                                      ]
                                  )
                              )
                              ->setReceiver($this->setReceiver($params['id']))
                              ->setMinApiVersion(3)
                              ->setText(Yii::$app->smsSender->messageText($params['event_time_start']))
                              ->setKeyboard(
                                  (new Keyboard())
                                      ->setButtons(
                                          [
                                              (new Button())
                                                  ->setColumns('3')
                                                  ->setBgColor('#7f8c8d')
                                                  ->setTextSize('regular')
                                                  ->setActionType('reply')
                                                  ->setActionBody('next')
                                                  ->setText('Следующие'),
                                              (new Button())
                                                  ->setColumns('3')
                                                  ->setBgColor('#7f8c8d')
                                                  ->setTextSize('regular')
                                                  ->setActionType('reply')
                                                  ->setActionBody('previous')
                                                  ->setText('Предыдущие')
                                          ]
                                      )
                              ));
    }
}