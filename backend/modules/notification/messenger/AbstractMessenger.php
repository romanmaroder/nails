<?php


namespace backend\modules\notification\messenger;


use backend\modules\notification\messenger\interfaces\MessengerInterface;

abstract class AbstractMessenger implements MessengerInterface
{
   abstract public function send(array $params);
}

