<?php


namespace common\components\sms;

/**
 * Interface SmsSenderInterface
 *
 * @package common\components\sms
 */
interface SmsSenderInterface
{

    public function checkOperatingSystem():string;

    public function checkTimeOfDay():string;

    public function messageText(string $dataEvent, string $greeting ):string;

}