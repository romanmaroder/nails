<?php

namespace backend\modules\viber\controllers;

use backend\modules\notification\AppMessenger;
use backend\modules\viber\models\Viber;
use common\models\Event;
use Exception;
use Viber\Api\Keyboard;
use Viber\Api\Keyboard\Button;
use Viber\Api\Message\Sticker;
use Viber\Api\Message\Text;
use Viber\Api\Sender;
use backend\modules\viber\api\ViberBot;
use Viber\Client;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * ViberController controller for the `viber` module
 */
class ViberController extends Controller
{


    public function beforeAction($action)//Обязательно нужно отключить Csr валидацию, так не будет работать
    {
        $this->enableCsrfValidation = ($action->id !== "setup");
        $this->enableCsrfValidation = ($action->id !== "webhook");
        return parent::beforeAction($action);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['setup', 'webhook'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                ],
            ],
        ];
    }

    public function actionSetup()
    {

        try {
            $client = new Client(['token' => Yii::$app->params['viber']['viberToken']]);
            $result = $client->setWebhook( Yii::$app->params['viber']['viberWebhook']);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function actionWebhook()
    {
        $botSender = new Sender(
            [
                'name' => Yii::$app->params['viber']['viberBotName'],
                'avatar' => Yii::$app->params['viber']['viberBotAvatar'],
            ]
        );

        try {
            $bot = new ViberBot(['token' => Yii::$app->params['viber']['viberToken']]);
            $messenger = new AppMessenger();

            $bot->onConversation(
                function ($event) use ($bot, $botSender) {
                    // Пользователь вошел в чат
                    // Разрешается написать только одно сообщение
                    $receiverId = $event->getUser()->getId();

                    $user = $event->getUser()->getName();
                    return (new Text())
                        ->setSender($botSender)
                        ->setReceiver($receiverId)
                        ->setText(
                            Yii::$app->smsSender->checkTimeOfDay() . $user . '.'
                        )
                        ->setKeyboard(
                            (new Keyboard())
                                ->setButtons(
                                    [
                                        (new Button())
                                            ->setBgColor('#7f8c8d')
                                            ->setTextSize('regular')
                                            ->setActionType('reply')
                                            ->setActionBody('start')
                                            ->setText('СТАРТ'),
                                    ]
                                )
                        );
                }
            )
                ->onSubscribe(
                    function ($event) use ($bot, $botSender) {
                        // Пользователь подписался на чат
                        $receiverId = $event->getUser()->getId();
                        $bot->getClient()->sendMessage(
                            (new Text())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setText('Спасибо за подписку!')
                                ->setKeyboard(
                                    (new Keyboard())
                                        ->setButtons(
                                            [
                                                (new Button())
                                                    ->setBgColor('#7f8c8d')
                                                    ->setTextSize('regular')
                                                    ->setActionType('reply')
                                                    ->setActionBody('start')
                                                    ->setText('СТАРТ'),
                                            ]
                                        )
                                )
                        );
                    }
                )
                // Requesting a phone number from a user
                ->onText(
                    '|start|si',
                    function ($event) use ($bot, $botSender) {
                        // Напечатали 'Hello'
                        $receiverId = $event->getSender()->getId();
                        $receiverName = $event->getSender()->getName();


                        if (Viber::getOldId($receiverId) == $receiverId) {
                            $bot->getClient()->sendMessage(
                                (new Text())
                                    ->setSender($botSender)
                                    ->setReceiver($receiverId)
                                    ->setMinApiVersion(3)
                                    ->setText($receiverName . '. Какие свои записи Вы хотите получить?')
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
                                    )
                            );
                        } else {
                            $bot->getClient()->sendMessage(
                                (new Text())
                                    ->setSender($botSender)
                                    ->setReceiver($receiverId)
                                    ->setMinApiVersion(3)
                                    ->setText($receiverName . '. Нам необходим Ваш номер телефона.')
                                    ->setKeyboard(
                                        (new Keyboard())
                                            ->setButtons(
                                                [
                                                    (new Button())
                                                        ->setActionType('share-phone')
                                                        ->setActionBody('reply')
                                                        ->setText('Отправить номер телефона')
                                                ]
                                            )
                                    )
                            );
                        }
                    }
                )
                // Verifying a user's phone number
                ->onContact(
                    function ($event) use ($bot, $botSender,$messenger) {
                        $clientPhone = $event->getMessage()->getPhoneNumber();
                        $receiverId = strval($event->getSender()->getId());
                        $receiverName = $event->getSender()->getName();

                        $user_by_phone = $messenger->findUser(strval($clientPhone));

                        if ($user_by_phone->id) {

                            Viber::start($receiverId, $receiverName, $user_by_phone->id);
                            $bot->getClient()->sendMessage(
                                (new Text())
                                    ->setSender($botSender)
                                    ->setReceiver($receiverId)
                                    ->setMinApiVersion(3)
                                    ->setText($receiverName . '. Какие свои записи Вы хотите получить?')
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
                                    )

                            );
                        } else {
                            $bot->getClient()->sendMessage(
                                (new Sticker())
                                    ->setSender($botSender)
                                    ->setReceiver($receiverId)
                                    ->setStickerId(24803)

                            );
                            $bot->getClient()->sendMessage(
                                (new Text())
                                    ->setSender($botSender)
                                    ->setReceiver($receiverId)
                                    ->setMinApiVersion(3)
                                    ->setText(
                                        $receiverName . ', возможно, мы Вас знаем по другому номеру телефона?'
                                        . PHP_EOL . 'Напишите своё *ИМЯ* и *ТЕЛЕФОН* через пробел.'
                                        . PHP_EOL . 'Пример:'
                                        . PHP_EOL .'*Лена* _380999999999_'
                                        . PHP_EOL .'или'
                                        . PHP_EOL .' *Елена* _+380999999999_'
                                    )

                            );
                        }
                    }
                )
                // Regular Expression Input Validation
                ->onText(
                    '|^([а-яА-я}]+\s{1}\+?380\d{9}$)|musi',
                    function ($event) use ($bot, $botSender,$messenger) {
                        $receiverId = $event->getSender()->getId();
                        $receiverName = $event->getSender()->getName();

                        $info = explode(" ", $event->getMessage()->getText());

                        if (preg_match(Yii::$app->params['namePattern'], $info[0]) === 1
                            && preg_match(Yii::$app->params['phonePattern'], $info[1]) === 1) {
                            $nameInfo = $info[0];
                            $phoneInfo = $messenger->convertPhone($info[1]);
                            $user = $messenger->findUserByNameAndPhone($nameInfo, $phoneInfo);


                            if (isset($user['id'])) {
                                Viber::start($receiverId, $receiverName, $user['id']);
                                $bot->getClient()->sendMessage(
                                    (new Sticker())
                                        ->setSender($botSender)
                                        ->setReceiver($receiverId)
                                        ->setStickerId(24824)

                                );
                                $bot->getClient()->sendMessage(
                                    (new Text())
                                        ->setSender($botSender)
                                        ->setReceiver($receiverId)
                                        ->setMinApiVersion(3)
                                        ->setText($receiverName . '. Какие свои записи Вы хотите получить?')
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
                                        )
                                );
                            } else {
                                $bot->getClient()->sendMessage(
                                    (new Text())
                                        ->setSender($botSender)
                                        ->setReceiver($receiverId)
                                        ->setText(
                                            $receiverName . ', под этим номером и именем Вы у нас не зарегистрированы.'
                                        )
                                );
                            }
                        }
                    }
                )
                // Get next user records
                ->onText(
                    '|next|',
                    function ($event) use ($bot, $botSender) {
                        $receiverId = $event->getSender()->getId();

                        $user_event_id = Viber::getUserId($receiverId);

                        if ($eventNext = Event::findNextClientEvents($user_event_id)) {
                            $reply = "Следующая запись: \n";
                            foreach ($eventNext as $item) {
                                $reply .= '*' . Yii::$app->formatter->asDatetime(
                                        $item['event_time_start'],
                                        'php:d M Y на H:i'
                                    ) . "* - _"
                                    . $item['description'] . "_\n";
                            }
                        } else {
                            $reply = "Вы еще не записались.";
                        }
                        $bot->getClient()->sendMessage(
                            (new Text())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setMinApiVersion(3)
                                ->setText($reply)
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
                                )

                        );




                    }
                )
                // Retrieve previous records of a user
                ->onText(
                    '|previous|',
                    function ($event) use ($bot, $botSender) {
                        $receiverId = $event->getSender()->getId();

                        $user_event_id = Viber::getUserId($receiverId);

                        if ($eventPrevious = Event::findPreviousClientEvents($user_event_id)) {
                            $reply = "Предыдущая запись: \n";
                            foreach ($eventPrevious as $item) {
                                $reply .= '*' . Yii::$app->formatter->asDatetime(
                                        $item['event_time_start'],
                                        'php:d M Y на H:i'
                                    ) . "* - _"
                                    . $item['description'] . "_\n";
                            }
                        } else {
                            $reply = "У вас нет предыдущих записей.";
                        }
                        $bot->getClient()->sendMessage(
                            (new Text())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setMinApiVersion(3)
                                ->setText($reply)
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
                                )

                        );

                    }
                )
                // User enters incorrect data
                ->onText(
                    '|\w+|u',
                    function ($event) use ($bot, $botSender) {
                        $receiverId = $event->getSender()->getId();
                        $receiverName = $event->getSender()->getName();
                        $bot->getClient()->sendMessage(
                            (new Sticker())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setStickerId(24803)

                        );
                        $bot->getClient()->sendMessage(
                            (new Text())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setText($receiverName . ', Вы где-то ошиблись! Попробуйте еще раз.')
                                ->setKeyboard(
                                    (new Keyboard())
                                        ->setButtons(
                                            [
                                                (new Button())
                                                    ->setBgColor('#7f8c8d')
                                                    ->setTextSize('regular')
                                                    ->setActionType('reply')
                                                    ->setActionBody('start')
                                                    ->setText('СТАРТ'),
                                            ]
                                        )
                                )
                        );
                    }
                );

            $bot->run();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }




}
