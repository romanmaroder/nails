<?php


namespace backend\controllers;


use Telegram\Bot\Api;
use yii\filters\AccessControl;
use yii\web\Controller;

class TelegramController extends Controller
{
    public function beforeAction($action)//Обязательно нужно отключить Csr валидацию, так не будет работать
    {
        $this->enableCsrfValidation = ($action->id !== "webhook");
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['webhook'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Bot initialization
     *
     * @return \Telegram\Bot\Api
     */
    public function bot(): Api
    {
        return new Api('1903642753:AAGQ18pg2H2iUJJ6ogU3s5wmyRAnq1eTsmk');
    }

    public function actionWebhook()
    {
        $result = $this->bot()->getWebhookUpdates();

        $text       = $result['message']['text'];
        $chat_id    = $result['message']['chat']['id'];
        $name       = $result['message']['from']['username'];
        $first_name = $result['message']['from']['first_name'];
        $last_name  = $result['message']['from']['last_name'];
        if ($text == "/start") {
            $reply        = "Menu:";
            $reply_markup = $this->bot()->replyKeyboardMarkup(
                [
                    'keyboard'          => $this->botMenu(),
                    'resize_keyboard'   => true,
                    'one_time_keyboard' => false
                ]
            );
            $this->bot()->sendMessage(
                [
                    'chat_id'      => $chat_id,
                    'text'         => $reply,
                    'reply_markup' => $reply_markup
                ]
            );
        } elseif ($text == "Hello") {
            $reply        = "Hello ".$first_name." ".$last_name;
            $reply_markup = $this->bot()->replyKeyboardMarkup(
                [
                    'keyboard'          => $this->botMenu(),
                    'resize_keyboard'   => true,
                    'one_time_keyboard' => false
                ]
            );
            $this->bot()->sendMessage(
                [
                    'chat_id'      => $chat_id,
                    'text'         => $reply,
                    'reply_markup' => $reply_markup
                ]
            );
        }
        elseif ($text == "Bye") {
            $reply        = "Bye ".$first_name." ".$last_name;
            $reply_markup = $this->bot()->replyKeyboardMarkup(
                [
                    'keyboard'          => $this->botMenu(),
                    'resize_keyboard'   => true,
                    'one_time_keyboard' => false
                ]
            );
            $this->bot()->sendMessage(
                [
                    'chat_id'      => $chat_id,
                    'text'         => $reply,
                    'reply_markup' => $reply_markup
                ]
            );
        }
    }

    /**
     * Creating bot menu buttons
     *
     * @return array
     */
    public function botMenu(): array
    {
        return [['Hello'], ['Bye']];
    }
}