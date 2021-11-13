<?php


namespace backend\controllers;


use backend\models\Telegram;
use common\models\Event;
use Telegram\Bot\Api;
use Yii;
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
        #return new Api('1903642753:AAGQ18pg2H2iUJJ6ogU3s5wmyRAnq1eTsmk');
        return new Api(Yii::$app->params['telegramToken']);
    }

    public function actionWebhook()
    {
        $result = $this->bot()->getWebhookUpdates();
        file_put_contents(__DIR__.'/logs.txt', print_r($result, 1), FILE_APPEND);

        $text          = $result['message']['text'];
        $chat_id       = $result['message']['chat']['id'];
        $name          = $result['message']['from']['username'];
        $first_name    = $result['message']['from']['first_name'];
        $last_name     = $result['message']['from']['last_name'];
        $username      = $first_name." ".$last_name;
        $old_id        = Telegram::getOldId($chat_id);
        $user_find     = User::findByUserPhone($text);
        $users_id      = $user_find->id;
        $user_event_id = Telegram::getUserId($chat_id);


        /*$text       = $result['message']['text'];
        $chat_id    = $result['message']['chat']['id'];
        $name       = $result['message']['from']['username'];
        $first_name = $result['message']['from']['first_name'];
        $last_name  = $result['message']['from']['last_name'];*/

        if ($text == "/start") {
            $reply = Yii::$app->smsSender->checkTimeOfDay().' '.$username;

            if ($old_id->chat_id !== $chat_id) {
                $reply = "Напишите номер Вашего телефона";
            } else {
                $reply_markup = $this->bot()->replyKeyboardMarkup(
                    [
                        'keyboard'          => $this->botMenuEvents(),
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
        } elseif ($text == "Следующая запись") {
            if ($eventNext = Event::findNextClientEvents($user_event_id)) {
                $reply = "Следующая запись: \n";
                foreach ($eventNext as $item) {
                    $reply .= date(
                            'd-m-Y H:i',
                            strtotime($item['event_time_start'])
                        )." - ".$item['description']."\n";
                }
            } else {
                $reply = "У вас нет записей";
            }

            $this->bot()->sendMessage(
                [
                    'chat_id'    => $chat_id,
                    'text'       => "<i>$reply</i>",
                    'parse_mode' => 'HTML'
                ]
            );
        } elseif ($text == "Предыдущая запись") {
            if ($eventPrevious = Event::findPreviousClientEvents($user_event_id)) {
                $reply = "Предыдущая запись: \n";
                foreach ($eventPrevious as $item) {
                    $reply .= date(
                            'd-m-Y',
                            strtotime($item['event_time_start'])
                        )." - ".$item['description']."\n";
                }
            } else {
                $reply = "У вас нет записей";
            }
            $this->bot()->sendMessage(
                [
                    'chat_id'    => $chat_id,
                    'text'       => "<i>$reply</i>",
                    'parse_mode' => 'HTML'
                ]
            );
        } elseif (preg_match(Yii::$app->params['phonePattern'], $text) == 0) {
            $reply = "Номер введен не правильно";
            $this->bot()->sendMessage(
                [
                    'chat_id' => $chat_id,
                    'text'    => $reply,
                ]
            );
        } elseif (preg_match(Yii::$app->params['phonePattern'], $text)) {
            Telegram::start($chat_id, $name, $username, $users_id, $old_id);
            $reply        = "Выберите действие";
            $reply_markup = $this->bot()->replyKeyboardMarkup(
                [
                    'keyboard'          => $this->botMenuEvents(),
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
    public function botMenuGreetings(): array
    {
        return [['Привет'], ['Пока']];
    }

    /**
     * Creating bot menu buttons
     *
     * @return array
     */
    public function botMenuEvents(): array
    {
        return [['Следующая запись', 'Предыдущая запись']];
    }
}