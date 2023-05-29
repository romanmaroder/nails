<?php

namespace common\extensions;

use Viber\Api\Message\Text;
use Viber\Bot;
use Viber\Api\Sender;


class Viberbot
{
    //public $bot = 777;
    public $cols = '999';

    public function CreateOperation()
    {
        $apiKey    = '4db2149cd9e7d16a-ab2f5cf585a35d04-f2b1eb9b43dd827';
        $botSender = new Sender(
            [
                'name'   => 'Мой бот',
                'avatar' => 'https://developers.viber.com/img/favicon.ico',
            ]
        );
        try {
            $bot = new Bot(['token' => $apiKey]);
            $bot
                ->getSignHeaderValue()
                ->onConversation(
                    function ($event) use ($bot, $botSender) {
                        // это событие будет вызвано, как только пользователь перейдет в чат
                        // вы можете отправить "привествие", но не можете посылать более сообщений
                        return (new Text())
                            ->setSender($botSender)
                            ->setText("Can i help you?");
                    }
                )
                ->onText(
                    '|\d{1}|is',
                    function ($event) use ($bot, $botSender) {
                        // это событие будет вызвано если пользователь пошлет сообщение
                        // которое совпадет с регулярным выражением
                        $bot->getClient()->sendMessage(
                            (new Text())
                                ->setSender($botSender)
                                ->setReceiver($event->getSender()->getId())
                                ->setText("I do not know YO!)")
                                /*->setKeyboard(
                                    (new \Viber\Api\Keyboard())
                                        ->setButtons(
                                            [
                                                (new \Viber\Api\Keyboard\Button())
                                                    ->setColumns(3)
                                                    ->setRows(2)
                                                    ->setActionType('reply')
                                                    ->setActionBody('btn-click')
                                                    ->setText($this->cols)
                                                -- > setImage('https://mydomen.com/button.jpg')

                                                (
                                                    new \Viber\Api\Keyboard\Button()
                                                )
                                                    ->setColumns(3)
                                                    ->setRows(2)
                                                    ->setActionType('reply')
                                                    ->setActionBody('btn-click')
                                                    //->setText($this->cols)
                                                    ->setImage('https://mydomen.com/button.jpg')
                                            ]
                                        )
                                )*/

                        );
                    }
                )
                ->run();
        } catch (Exception $e) {
           echo 'Пиздец';
        }
    }


}