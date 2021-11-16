<?php


namespace common\components\sms;


use DateTime;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;


/**
 * Class SmsSender
 * @package common\components\sms
 */
class SmsSender extends Component implements SmsSenderInterface
{




    protected const MORNING = "Доброе утро".PHP_EOL;
    protected const DAY = "Добрый день".PHP_EOL;
    protected const EVENING = "Добрый вечер".PHP_EOL;
    protected const NIGHT = "Доброй ночи".PHP_EOL;

    /**
     * Checking what operating system the user is using
     * @property $params
     * @return  string
     */
   final public function checkOperatingSystem(): string
    {
        preg_match("/iPhone|Android|iPad|iPod|webOS/", $_SERVER['HTTP_USER_AGENT'], $matches);
        $os = current($matches);

        switch ($os) {
            case 'iPod':
            case 'iPhone':
            case 'iPad':
                $params = '&body=';
                break;
            case 'Android':
                $params = '?body=';
                break;
            default:
                $params = '?body=';
        }
        return $params;
    }

    /**
     * Checking the time of day for the greeting
     * @return string
     */
   final public function checkTimeOfDay(): string
    {
        $greeting = '';
        $hour = Yii::$app->formatter->asTime(date("H:i"));


        if ($hour >= 04) {
            $greeting = self::MORNING;
        }
        if ($hour >= 10) {
            $greeting = self::DAY;
        }
        if ($hour >= 16) {
            $greeting = self::EVENING;
        }
        if ($hour >= 22 or $hour < 04) {
            $greeting = self::NIGHT;
        }

        return $greeting;
    }


    /**
     * The text of the sent message
     * @param string $dataEvent
     * @param string|null $greeting
     * @return string
     * @throws InvalidConfigException
     */
    final public function messageText(string $dataEvent, string $greeting = null ): string
    {
        $greeting = self::checkTimeOfDay();
        $data = Yii::$app->formatter->asDatetime($dataEvent,'php:d M Y на H:i');

        $launchDate = new DateTime(Yii::$app->formatter->asDate($dataEvent,'php:d-m-Y'));
        $today = new DateTime();
        $daysToLaunch = $today->diff($launchDate, false)->days;


        if ($daysToLaunch >= 1) {
            return $greeting.'. У Вас следующая запись '.$data.'.';
        }

        return $greeting.'. У Вас запись '.$data .'. Вы будете?';
    }
}
