<?php


namespace backend\modules\notification;


use Yii;

abstract class AbstractMessenger implements MessengerInterface
{
   abstract public function send(array $params);
}

