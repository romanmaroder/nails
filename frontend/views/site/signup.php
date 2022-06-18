<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap4\ActiveForm */

/* @var $model SignupForm */

use frontend\models\SignupForm;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\captcha\Captcha;

$this->title                   = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup" id="wrap-background">
    <div class="row">
        <div class="col-12 col-sm-6 mx-auto text-center">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Пожалуйста, заполните следующие поля для регистрации:</p>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 mx-auto">
            <?php
            $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'verifyCode')->widget(
                Captcha::class,
                [

                    //'captchaAction' => 'site/captcha', // uncomment and change if your not in the default controller or a module
                    'template' => '<div class="row"><div class="col-lg-2 my-auto">{image}</div><div class="col-lg-10">{input}</div></div>',
                ]
            )
                ->hint('Подсказка: нажмите на уравнение, чтобы обновить' )
                ->label(
                    'Подтвердить код',
                    [
                       // 'class' => 'd-none',
                        'for'   => 'signupform-verifycode'
                    ]
                )
            ?>

            <div class='form-group text-center mt-lg-5'>
                <?= Html::submitButton(
                    'Зарегистрироваться', [
                                            'class' => 'btn btn-primary',
                                            'name'  => 'signup-button'
                                        ]
                ) ?>
            </div>

            <?php
            ActiveForm::end(); ?>
        </div>
    </div>
</div>
