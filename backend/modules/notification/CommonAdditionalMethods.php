<?php


namespace backend\modules\notification;


use common\models\User;

trait CommonAdditionalMethods
{
    /**
     * Reducing a phone number to the form +380(xx)xxx-xx-xx
     *
     * @param string $phone
     *
     * @return string
     */
    protected function convertPhone(string $phone){
        $cleaned = preg_replace('/[^\W*[:digit:]]/', '', $phone);

        if (strlen($phone) <= 13) {
            preg_match('/\W*(\d{2})(\d{3})(\d{3})(\d{2})(\d{2})/', $cleaned, $matches);

            return "+{$matches[1]}({$matches[2]}){$matches[3]}-{$matches[4]}-{$matches[5]}";
        } else {
            return $cleaned;
        }
    }

    /**
     * Searching for a user by phone number
     *
     * @param string $phone
     *
     * @return User
     */
    protected function findUser(string $phone): ?User
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
    protected function findUserByNameAndPhone(string $name, string $phone)
    {
        return User::findByUserNameAndPhone($name, $phone);
    }
}