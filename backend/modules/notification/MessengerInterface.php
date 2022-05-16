<?php


namespace backend\modules\notification;


interface MessengerInterface
{

    public function send(array $params);
}