<?php


namespace backend\modules\telegram\controllers;


use backend\modules\notification\AppMessenger;
use backend\modules\telegram\api\TelegramBot;
use backend\modules\telegram\models\Telegram;
use common\models\Event;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Yii;
use yii\base\InvalidConfigException;
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
     * @return TelegramBot
     */
    public function telegramBot(): TelegramBot
    {
        return new TelegramBot();
    }

    /**
     * Bot functionality
     * @throws InvalidConfigException
     * @throws TelegramSDKException
     */
    public function actionWebhook()
    {
        $result = $this->telegramBot()->getWebhookUpdates();
        $messenger = new AppMessenger();
        #file_put_contents(__DIR__ . '/logs.txt', print_r($result, 1), FILE_APPEND);

        $text          = $result['message']['text'];
        $chat_id       = $result['message']['chat']['id'];
        $name          = $result['message']['from']['username'];
        $first_name    = $result['message']['from']['first_name'];
        $last_name     = $result['message']['from']['last_name'];
        $username      = $first_name . " " . $last_name;
        $old_id        = Telegram::getOldId($chat_id);
        $user_event_id = Telegram::getUserId($result['callback_query']['message']['chat']['id']);

        if ($text == "/start") {

            if ($old_id->chat_id === $chat_id) {
                $this->telegramBot()->sendMessage(
                    [
                        'chat_id'      => $chat_id,
                        'text'         => Yii::$app->smsSender->checkTimeOfDay() . $username . '. Для продолжение работы отправьте свой номер телефона',
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
                        'text'         => Yii::$app->smsSender->checkTimeOfDay() . "{$username}, что Вы хотите узнать?",
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
                        . PHP_EOL . 'Для продолжения работы бот, единожды, запросит у Вас номер вашего телефона.'
                        . PHP_EOL . 'Отправить номер можно кнопкой <b>Отправить мой номер</b>.'
                        . PHP_EOL . 'После этого Вы сможете запрашивать у бота <b>Предыдущие</b> и <b>Следующие</b> свои записи ',
                    'parse_mode' => 'HTML'

                ]
            );
        } elseif (isset($result['message']['contact']['phone_number']) && $old_id->chat_id == $chat_id) {
            $this->telegramBot()->sendMessage(
                [
                    'chat_id'      => $chat_id,
                    'text'         => 'Вы уже отправляли номер.' . PHP_EOL . ' Выберите команду /start чтобы продолжить',
                    'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                        [
                            'remove_keyboard' => true,
                        ]
                    )
                ]
            );
            $this->telegramBot()->sendSticker(
                [
                    'chat_id' => $chat_id,
                    'sticker' => 'CAACAgIAAxkBAAIT-GGW1wXsQ6dF1_XNookYWGNSPkLHAALSAANWnb0KDgVyNnWDNYoiBA'
                ]
            );
        } elseif (isset($result['message']['contact']['phone_number']) && $old_id->chat_id !== $chat_id) {
            $user_by_phone = $messenger->findUser($result['message']['contact']['phone_number']);

            if ($user_by_phone->id) {
                Telegram::start($chat_id, $name, $username, $user_by_phone->id, $old_id);

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
                $this->telegramBot()->sendSticker(
                    [
                        'chat_id' => $chat_id,
                        'sticker' => 'CAACAgIAAxkBAAITomGWzm_XqDCUmR4StusHvsu8y6P9AALjAANWnb0KD_gizK2mCzciBA'
                    ]
                );
                $this->telegramBot()->sendMessage(
                    [
                        'chat_id'      => $chat_id,
                        'text'         => 'Хм! Возможно, мы Вас знаем по другому номеру телефона?'
                            . PHP_EOL . 'Напишите своё <b>ИМЯ</b> и <b>ТЕЛЕФОН</b> через пробел и я схожу проверю.'
                            . PHP_EOL . 'Пример:Лена <code>380999999999</code> или Елена <code>+380999999999</code>',
                        'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                            [
                                'remove_keyboard' => true,
                            ]
                        ),
                        'parse_mode'   => 'HTML'

                    ]
                );
            }
        } elseif (isset($result['callback_query']['message'])) {
            if ($result['callback_query']['data'] == 'next') {
                if ($eventNext = Event::findNextClientEvents($user_event_id)) {
                    $reply = "Следующая запись: \n";
                    foreach ($eventNext as $item) {
                        $reply .= "<b>" . Yii::$app->formatter->asDatetime(
                                $item['event_time_start'],
                                'php:d M Y на H:i'
                            ) . "</b> - <i> "
                            . $item['description'] . "</i>\n";
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
                        $reply .= "<b>" . Yii::$app->formatter->asDatetime(
                                $item['event_time_start'],
                                'php:d M Y на H:i'
                            ) . "</b> - <i> "
                            . $item['description'] . "</i>\n";
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
        } elseif (isset($text)) {
            $info = explode(" ", $text);
            if (preg_match(Yii::$app->params['namePattern'], $info[0]) === 1 && preg_match(
                    Yii::$app->params['phonePattern'],
                    $info[1]
                ) === 1) {
                $nameInfo  = $info[0];
                $phoneInfo = $messenger->convertPhone($info[1]);
                $user      = $messenger->findUserByNameAndPhone($nameInfo, $phoneInfo);


                if ($old_id->chat_id !== $chat_id) {
                    if (isset($user['id'])) {
                        Telegram::start($chat_id, $name, $username, $user['id'], $old_id);
                        $this->telegramBot()->sendSticker(
                            [
                                'chat_id' => $chat_id,
                                'sticker' => 'CAACAgIAAxkBAAIULWGW2Sdp1-EHRGCOtngAAmwNHFwgAALYAANWnb0KiQndv0vxFCciBA'
                            ]
                        );
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
                        $this->telegramBot()->sendSticker(
                            [
                                'chat_id' => $chat_id,
                                'sticker' => 'CAACAgIAAxkBAAITx2GW012SnHzmLs9vgU6eKxWGYDC9AALlAANWnb0KCAsWZJUF0YoiBA'
                            ]
                        );
                        $this->telegramBot()->sendMessage(
                            [
                                'chat_id' => $chat_id,
                                'text'    => 'Что то не то! Попробуйте еще раз.',

                            ]
                        );
                    }
                } else {
                    $this->telegramBot()->sendMessage(
                        [
                            'chat_id'      => $chat_id,
                            'text'         => 'Вы уже отправляли номер.' . PHP_EOL . ' Выберите команду /start чтобы продолжить',
                            'reply_markup' => $this->telegramBot()->replyKeyboardMarkup(
                                [
                                    'remove_keyboard' => true,
                                ]
                            )
                        ]
                    );
                    $this->telegramBot()->sendSticker(
                        [
                            'chat_id' => $chat_id,
                            'sticker' => 'CAACAgIAAxkBAAIT-GGW1wXsQ6dF1_XNookYWGNSPkLHAALSAANWnb0KDgVyNnWDNYoiBA'
                        ]
                    );
                }
            } else {
                $this->telegramBot()->sendMessage(
                    [
                        'chat_id' => $chat_id,
                        'text'    => 'Некорректно указаны данные',

                    ]
                );
                $this->telegramBot()->sendSticker(
                    [
                        'chat_id' => $chat_id,
                        'sticker' => 'CAACAgIAAxkBAAIUCGGW2EMdRa-wcNdIWiOOQaiQmzp8AALfAANWnb0KEEh8kSOlJ_0iBA'
                    ]
                );
            }
        }
    }

    /**
     * Creating bot menu buttons
     *
     * @return array
     */
    public function botMenuEvents(): array
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


}
