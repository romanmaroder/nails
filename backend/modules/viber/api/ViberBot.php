<?php
namespace backend\modules\viber\api;

use Viber\Api\Event;
use Viber\Bot;
use Viber\Bot\Manager;

class ViberBot extends Bot
{
    public function onContact(\Closure $handler) {
        $this->managers[] = new Manager(function (Event $event) {
            return (
                $event instanceof \Viber\Api\Event\Message && $event->getMessage() instanceof \Viber\Api\Message\Contact
            );
        }, $handler);
        return $this;
    }
}