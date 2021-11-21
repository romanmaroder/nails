<?php

namespace backend\modules\viber\controllers;

use backend\modules\viber\models\Viber;
use common\models\User;
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
        $this->webhookUrl .= 'viber/viber/webhook';

        try {
            $client = new Client(['token' => Yii::$app->params['viber']['viberToken']]);
            $result = $client->setWebhook($this->webhookUrl);
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
            $bot->onConversation(
                function ($event) use ($bot, $botSender) {
                    // Пользователь вошел в чат
                    // Разрешается написать только одно сообщение
                    $receiverId = $event->getUser()->getId();

                    $user = $event->getUser()->getName();
                    $bot->getClient()->sendMessage(
                        (new Text())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setText(
                                Yii::$app->smsSender->checkTimeOfDay() . $user . ', для продолжения напишите "Привет".'
                            )
                    );
                    $bot->getClient()->sendMessage(
                        (new Text())
                            ->setSender($botSender)
                            ->setReceiver($receiverId)
                            ->setText(
                                Yii::$app->smsSender->checkTimeOfDay(
                                ) . $user . ', для продолжения напишите "Привет123132".'
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
                        );
                    }
                )
                ->onText(
                    '|Привет|si',
                    function ($event) use ($bot, $botSender) {
                        // Напечатали 'Hello'
                        $receiverId = $event->getSender()->getId();
                        $user = $event->getSender()->getName();
                        $user_id = $event->getSender()->getId();
                        $user_avatar = $event->getSender()->getAvatar();

                        $bot->getClient()->sendMessage(
                            (new Text())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setMinApiVersion(3)
                                ->setText($user . '. Нам необходим Ваш номер телефона.' . $user_id)
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
                ->onContact(
                    function ($event) use ($bot, $botSender) {
                        $clientPhone = $event->getMessage()->getPhoneNumber();
                        $receiverId = strval($event->getSender()->getId());
                        $user_by_phone = $this->findUser($clientPhone);
                        if ($user_by_phone->id) {
                        }
                        $bot->getClient()->sendMessage(
                            (new Text())
                                ->setSender($botSender)
                                ->setReceiver($receiverId)
                                ->setMinApiVersion(3)
                                ->setText($clientPhone)

                        );
                    }
                );


            $bot->run();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
        # return '';
    }

    /**
     * Searching for a user by phone number
     *
     * @param string $phone
     *
     * @return User
     */
    public function findUser(string $phone): ?User
    {
        return User::findByUserPhone($this->convertPhone($phone));
    }

    /**
     * Search for a user by name and phone number
     *
     * @param string $name
     * @param string $phone
     *
     * @return User|false
     */
    public function findUserByNameAndPhone(string $name, string $phone)
    {
        return User::findByUserNameAndPhone($name, $phone);
    }

    /**
     * Reducing a phone number to the form +380(xx)xxx-xx-xx
     *
     * @param string $phone
     *
     * @return string
     */
    public function convertPhone(string $phone): string
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
