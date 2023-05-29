<?php

namespace backend\modules\viber\api;

use Viber\Api\Event;
use Viber\Bot;
use Viber\Bot\Manager;
use Yii;

class ViberBot extends Bot
{
    public function __construct(array $options = null)
    {
        $options['token'] = Yii::$app->params['viber']['viberToken'];
        parent::__construct($options);
    }
    
    public function onContact(\Closure $handler)
    {
        $this->managers[] = new Manager(
            function (Event $event) {
                return (
                    $event instanceof \Viber\Api\Event\Message && $event->getMessage() instanceof \Viber\Api\Message\Contact
                );
            }, $handler
        );
        return $this;
    }
}