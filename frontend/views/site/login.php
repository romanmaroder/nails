<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Зайдете?';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните следующие поля для входа:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <!--<div style="color:#999;margin:1em 0">
                    Забыли пароль? <?/*= Html::a('сбросить пароль', ['site/request-password-reset']) */?>.
                    <br>
					Требуется новое письмо с подтверждением? <?/*= Html::a('выслать',
																		 ['site/resend-verification-email']) */?>
                </div>-->

                <div class="form-group">
                    <?= Html::submitButton('Авторизироваться', ['class' => 'btn btn-primary', 'name' =>
						'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
