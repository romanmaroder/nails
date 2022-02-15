<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            /* [
               'username',
                'unique',
                'targetClass' => '\common\models\User',
                'message'     => 'This username has already been taken.'
            ],*/
            ['username', 'string', 'min' => 2, 'max' => 50],
            ['username', 'match', 'pattern' => '/^[A-zА-я\s]+$/u'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message'     => 'Этот адрес электронной почты уже занят.'
            ],

            ['password', 'required'],

            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }


    public function attributeLabels()
    {
        return [
            'username' => 'Имя',
            'password' => 'Пароль'
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user    = new User();

        $user->username = $this->username;
        $user->email    = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        //Добавляем роль по умолчанию для каждого зарегестрированного
        if ($user->save()) {
            $auth             = Yii::$app->authManager;
            $role             = $auth->getRole('user');
            $auth->assign($role, $user->id);
            return $user && $this->sendEmail($user);
        }
        return null;
    }

    /**
     * Sends confirmation email to user
     *
     * @param  User  $user  user model to with email should be send
     *
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Регистрация аккаунта на '.Yii::$app->name)
            ->send();
    }
}
