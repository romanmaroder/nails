<?php

namespace backend\models;

use common\models\User;

class SignupClientForm extends User
{
    /**
     * При создании клиента через админ-панель
     * Пароль и почта пользователя по-умолчанию
     */
    protected const DEFAULT_PASSWORD = '11111111';
    protected const DEFAULT_EMAIL    = '@user.com';

    public function generateEmail()
    {
        $this->email = 'user' . rand(1, 5000) . self::DEFAULT_EMAIL;
    }

    public function defaultPassword(): string
    {
        return self::DEFAULT_PASSWORD;
    }
}