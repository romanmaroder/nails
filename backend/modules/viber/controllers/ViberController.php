<?php

namespace backend\modules\viber\controllers;

use Exception;
use Viber\Api\Keyboard;
use Viber\Api\Keyboard\Button;
use Viber\Api\Message\Text;
use Viber\Api\Sender;
use backend\modules\viber\api\ViberBot;
use Viber\Client;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `viber` module
 */
class ViberController extends Controller
{
    public $webhookUrl = 'https://sparkc.ru/admin/';

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
                        'allow'   => true,
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::class,
                'actions' => [
                ],
            ],
        ];
    }

    public function actionSetup()
    {
        $this->webhookUrl .= 'viber/viber/webhook';

        try {
            $client = new Client(['token' => Yii::$app->params['viber']['viberToken']]);
            $result = $client->setWebhook($this->webhookUrl);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage()."\n";
        }
    }

    public function actionWebhook()
    {
        $botSender = new Sender(
            [
                'name'   => Yii::$app->params['viber']['viberBotName'],
                'avatar' => Yii::$app->params['viber']['viberBotAvatar'],
            ]
        );

        try {
            $bot = new ViberBot(['token' => Yii::$app->params['viber']['viberToken']]);
            $bot->onSubscribe(
                function ($event) use ($bot, $botSender) {
                    // Пользователь подписался на чат
                    $receiverId = $event->getUser()->getId();
                    $bot->getClient()->sendMessage(
                        (new Text())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setText('Спасибо за подписку!')
                    );
                }
            )
                ->onConversation(
                    function ($event) use ($bot, $botSender) {
                        // Пользователь вошел в чат
                        // Разрешается написать только одно сообщение
                        $receiverId = $event->getUser()->getId();
                        $user       = $event->getUser()->getName();
                        $bot->getClient()->sendMessage(
                            (new Text())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setText(Yii::$app->smsSender->checkTimeOfDay() . $user.', для продолжения напишите "Привет".')
                        );
                    }
                )
                ->onText(
                    '|Привет|si',
                    function ($event) use ($bot, $botSender) {
                        // Напечатали 'Hello'
                        $receiverId = $event->getSender()->getId();
                        $user       = $event->getSender()->getName();
                        $user_id       = $event->getSender()->getId();
                        $user_avatar      = $event->getSender()->getAvatar();

                        $bot->getClient()->sendMessage(
                            (new Text())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setMinApiVersion(3)
                                ->setText($user.'. Нам необходим Ваш номер телефона.')
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
                )
                ->onContact(function ($event) use ($bot, $botSender) {
                    $clientPhone = $event->getMessage()->getPhoneNumber();
                    $receiverId = strval($event->getSender()->getId());

                    $bot->getClient()->sendMessage(
                        (new Text())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setMinApiVersion(3)
                            ->setText($clientPhone)

                    );
                })
                ->run();
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage()."\n";
        }
        # return '';
    }


    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
