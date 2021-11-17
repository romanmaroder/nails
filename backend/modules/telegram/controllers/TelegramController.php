<?php


namespace backend\modules\telegram\controllers;


use backend\modules\telegram\api\TelegramBot;
use backend\modules\telegram\models\Telegram;
use common\models\Event;
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
     * @return \backend\modules\telegram\api\TelegramBot
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function telegramBot(): TelegramBot
    {
        return new TelegramBot(Yii::$app->params['telegramToken']);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function actionWebhook()
    {
        $result = $this->telegramBot()->getWebhookUpdates();
        file_put_contents(__DIR__ . '/logs.txt', print_r($result, 1), FILE_APPEND);

        $text          = $result['message']['text'];
        $chat_id       = $result['message']['chat']['id'];
        $name          = $result['message']['from']['username'];
        $first_name    = $result['message']['from']['first_name'];
        $last_name     = $result['message']['from']['last_name'];
        $username      = $first_name." ".$last_name;
        $old_id        = Telegram::getOldId($chat_id);
        $user_event_id = Telegram::getUserId($result['callback_query']['message']['chat']['id']);


        if ($text == "/start") {
            if ($old_id->chat_id !== $chat_id) {
                $this->telegramBot()->sendMessage(
                    [
                        'chat_id'      => $chat_id,
                        'text'         => Yii::$app->smsSender->checkTimeOfDay(
                            ).$username.'. Для продолжение работы отправьте свой номер телефона',
                        'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                            [
                                'keyboard'          => $this->botMenuPhone(),
                                'resize_keyboard'   => true,
                                'one_time_keyboard' => true,

                            ]
                        )
                    ]
                );
            } else {
                $this->telegramBot()->sendMessage(
                    [
                        'chat_id'      => $chat_id,
                        'text'         => Yii::$app->smsSender->checkTimeOfDay()."{$username}, что Вы хотите узнать?",
                        'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                            [
                                'inline_keyboard'   => $this->botMenuEvents(),
                                'resize_keyboard'   => true,
                                'one_time_keyboard' => false,
                            ]
                        )
                    ]
                );
            }
        } elseif ($text == "/help") {
            $this->telegramBot()->sendMessage(
                [
                    'chat_id'    => $chat_id,
                    'text'       => 'Начать работу с ботом можно нажав кнопку <b>СТАРТ</b> или написав смс /start.'
                        .PHP_EOL.'Для продолжения работы бот, единожды, запросит у Вас номер вашего телефона.'
                        .PHP_EOL.'Отправить номер можно кнопкой <b>Отправить мой номер</b>.'
                        .PHP_EOL.'После этого Вы сможете запрашивать у бота <b>Предыдущие</b> и <b>Следующие</b> свои записи ',
                    'parse_mode' => 'HTML'

                ]
            );
        }elseif (isset($result['message']['contact']['phone_number']) && $old_id->chat_id == $chat_id){
            $this->telegramBot()->sendMessage(
                [
                    'chat_id'      => $chat_id,
                    'text'=>'Вы уже отправляли номер.'.PHP_EOL.' Выберите команду /start чтобы продолжить',
                    'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                        [
                            'remove_keyboard' => true,
                        ]
                    )
                ]
            );
        } elseif (isset($result['message']['contact']['phone_number']) && $old_id->chat_id !== $chat_id) {
            $user_id = $this->findUser($result['message']['contact']['phone_number']);
            if ($user_id) {
                Telegram::start($chat_id, $name, $username, $user_id, $old_id);

                $this->telegramBot()->sendMessage(
                    [
                        'chat_id'      => $chat_id,
                        'text'         => 'Что Вы хотите узнать?',
                        'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                            [
                                'inline_keyboard'   => $this->botMenuEvents(),
                                'resize_keyboard'   => true,
                                'one_time_keyboard' => false,
                            ]
                        )
                    ]
                );
            } else {
                $this->telegramBot()->sendMessage(
                    [
                        'chat_id' => $chat_id,  // TODO додумать логику при вводе некорректного номера
                        'text'    => 'Хм! Возможно, Вы с нами общаетесь по другому номеру телефона?',

                    ]
                );
                $this->telegramBot()->sendMessage(
                    [
                        'chat_id'      => $chat_id,  // TODO додумать логику при вводе некорректного номера
                        'text'         => 'Попробуйте ввести другой номер телефона в формате: <code>380999999999</code> или <code>+380999999999</code>',
                        'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                            [
                                'remove_keyboard' => true,
                            ]
                        ),
                        'parse_mode'   => 'HTML'
                    ]
                );
            }
        } elseif(isset($result['callback_query']['message'])) {
            if ($result['callback_query']['data'] == 'next') {
                if ($eventNext = Event::findNextClientEvents($user_event_id)) {
                    $reply = "Следующая запись: \n";
                    foreach ($eventNext as $item) {
                        $reply .= "<b>".Yii::$app->formatter->asDatetime(
                                $item['event_time_start'],
                                'php:d M Y на H:i'
                            )."</b> - <i> "
                            .$item['description']."</i>\n";
                    }
                } else {
                    $reply = "Вы еще не записались.";
                }
                $this->telegramBot()->answerCallbackQuery(
                    [
                        'callback_query_id' => $result['callback_query']['id'],
                    ]
                );
                $this->telegramBot()->sendMessage(
                    [
                        'chat_id'    => $result['callback_query']['message']['chat']['id'],
                        'text'       => $reply,
                        'parse_mode' => 'HTML'
                    ]
                );
            }
            if ($result['callback_query']['data'] == 'previous') {
                if ($eventPrevious = Event::findPreviousClientEvents($user_event_id)) {
                    $reply = "Предыдущая запись: \n";
                    foreach ($eventPrevious as $item) {
                        $reply .= "<b>".Yii::$app->formatter->asDatetime(
                                $item['event_time_start'],
                                'php:d M Y на H:i'
                            )."</b> - <i> "
                            .$item['description']."</i>\n";
                    }
                } else {
                    $reply = "У вас нет предыдущих записей";
                }
                $this->telegramBot()->answerCallbackQuery(
                    [
                        'callback_query_id' => $result['callback_query']['id'],
                    ]
                );
                $this->telegramBot()->sendMessage(
                    [
                        'chat_id'    => $result['callback_query']['message']['chat']['id'],
                        'text'       => $reply,
                        'parse_mode' => 'HTML'
                    ]
                );
            }
        }
        elseif ( preg_match(Yii::$app->params['phonePattern'], $text) == 1 ) {
            $user = $this->findUser($text);
$user_id = $user->id;
            if ($old_id->chat_id !== $chat_id){

                if ( $user_id) {
                    Telegram::start($chat_id, $name, $username, $user_id, $old_id);
                    $this->telegramBot()->sendMessage(
                        [
                            'chat_id'      => $chat_id,
                            'text'         => 'Что Вы хотите узнать?',
                            'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                                [
                                    'inline_keyboard'   => $this->botMenuEvents(),
                                    'resize_keyboard'   => true,
                                    'one_time_keyboard' => false,
                                ]
                            )
                        ]
                    );
                } else {

                    $this->telegramBot()->sendMessage(
                        [
                            'chat_id' => $chat_id,  // TODO додумать логику при вводе некорректного номера
                            'text'    => 'Клиента с таким номером у нас нет!',

                        ]
                    );
                }
            }else{


                $this->telegramBot()->sendMessage(
                    [
                        'chat_id'      => $chat_id,
                        'text'=>$username .', Вы уже присылали нам свой номер.',
                        'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                            [
                                'remove_keyboard' => true,
                            ]
                        )
                    ]
                );
            }

        }elseif (preg_match(Yii::$app->params['phonePattern'], $text) == 0 ) {
            $this->telegramBot()->sendMessage(
                [
                    'chat_id'      => $chat_id,
                    'text'=>'Номер введен неправильно',

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
     * @return
     */
    public function botMenuEvents()
    {
        return [
            [
                ['text' => 'Следующая запись', 'callback_data' => 'next'],
                ['text' => 'Предыдущая запись', 'callback_data' => 'previous']
            ],
            /*[
                ['text' => 'WebForMyself', 'url' => 'https://webformyself.com'],
                ['text' => 'Google', 'url' => 'https://google.com'],
            ],*/
        ];
    }

    /**
     * Creating bot menu buttons
     *
     * @return array
     */
    public function botMenuPhone(): array
    {
        return [
            [
                ['text' => 'Отправить мой номер', 'request_contact' => true],
            ],
        ];
    }

    public function findUser($phone)
    {
        return User::findByUserPhone($this->convertPhone($phone));
        #return  Telegram::find()->with('user')->where(['user_id'=>$user->id]);
    }

    public function convertPhone($phone)
    {
        $cleaned = preg_replace('/[^\W*[:digit:]]/', '', $phone);

        if (strlen($phone) <= 13) {
            preg_match('/\W*(\d{2})(\d{3})(\d{3})(\d{2})(\d{2})/', $cleaned, $matches);

            return "+{$matches[1]}({$matches[2]}){$matches[3]}-{$matches[4]}-{$matches[5]}";
        } else {
            return $cleaned;
        }
    }

}

