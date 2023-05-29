<?php


namespace backend\modules\notification\messenger\interfaces;

interface MessengerInterface
{

    public function send(array $params);
}