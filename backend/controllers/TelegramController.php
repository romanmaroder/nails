<?php


namespace backend\controllers;


use backend\models\Telegram;
use Telegram\Bot\Api;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\User;

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

        $text       = $result->getMessage()->getText();
        $chat_id    = $result->getMessage()->getChat()->getId();
        $name       = $result->getMessage()->getFrom()->getUsername();
        $first_name = $result->getMessage()->getFrom()->getFirstName();
        $last_name  = $result->getMessage()->getFrom()->getLastName();
        $username   = $first_name." ".$last_name;
        $old_id     = Telegram::getOldId($chat_id);
        $userfind   = User::findByUserPhone( $text );
        $users_id    = $userfind->id;
        /*$text       = $result['message']['text'];
        $chat_id    = $result['message']['chat']['id'];
        $name       = $result['message']['from']['username'];
        $first_name = $result['message']['from']['first_name'];
        $last_name  = $result['message']['from']['last_name'];*/

        if ($text == "/start") {
            $reply = "Меню:";
            if ($old_id->chat_id !== $chat_id) {
                $reply        = "Напишите номер Вашего телефона";
            } else {
                $reply_markup = $this->bot()->replyKeyboardMarkup(
                    [
                        'keyboard'          => $this->botMenuGreetings(),
                        'resize_keyboard'   => true,
                        'one_time_keyboard' => false
                    ]
                );
            }

            $this->bot()->sendMessage(
                [
                    'chat_id'      => $chat_id,
                    'text'         => $reply,
                    'reply_markup' => $reply_markup
                ]
            );
        } elseif ($text == "Привет") {
            $reply        = "Привет ".$first_name." ".$last_name;
            $reply_markup = $this->bot()->replyKeyboardMarkup(
                [
                    'keyboard'          => $this->botMenuGreetings(),
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
        } elseif ($text == "Пока") {
            $reply        = "Пока ".$first_name." ".$last_name;
            $reply_markup = $this->bot()->replyKeyboardMarkup(
                [
                    'keyboard'          => $this->botMenuGreetings(),
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
        elseif($text == $text){

            Telegram::start($chat_id, $name, $username, $users_id, $old_id);
            $reply =" Привет ". $userfind->username. " Ваша почта ".$userfind->email;

            $this->bot()->sendMessage([  'chat_id'      => $chat_id,
                                         'text'         => $reply,
                                      ]);
        }






    }

    /**
     * Creating bot menu buttons
     *
     * @return array
     */
    public function botMenuGreetings(): array
    {
        return [['Привет'], ['Пока']];
    }

    /**
     * Creating bot menu buttons
     *
     * @return array
     */
    public function botMenuPhone(): array
    {
        return [['Телефон']];
    }
}