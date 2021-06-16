<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */
$this->title = 'Логин';
?>
<div class="card">
	<div class="card-body login-card-body">
        <?php
        if (Yii::$app->getSession()->hasFlash('denide')):?>
            <?php
            echo '<p class="text-danger text-center"><b>'.Yii::$app->session->getFlash('denide').'</b></p>'; ?>
        <?php
        else: ?>
			<p class="login-box-msg">Войдите, чтобы начать сеанс</p>
        <?php
        endif;; ?>
        <?php
        $form = ActiveForm::begin(['id' => 'login-form']) ?>

        <?= $form->field(
            $model,
            'username',
            [
                'options'        => ['class' => 'form-group has-feedback'],
                'inputTemplate'  => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
                'template'       => '{beginWrapper}{input}{error}{endWrapper}',
                'wrapperOptions' => ['class' => 'input-group mb-3']
            ]
        )
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username'), 'autofocus' => true]) ?>

        <?= $form->field(
            $model,
            'password',
            [
                'options'        => ['class' => 'form-group has-feedback'],
                'inputTemplate'  => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
                'template'       => '{beginWrapper}{input}{error}{endWrapper}',
                'wrapperOptions' => ['class' => 'input-group mb-3']
            ]
        )
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

		<div class="row">
			<div class="col-8">
                <?= $form->field($model, 'rememberMe')->checkbox(
                    [
                        'template'     => '<div class="icheck-primary">{input}{label}</div>',
                        'labelOptions' => [
                            'class' => ''
                        ],
                        'uncheck'      => null
                    ]
                ) ?>
			</div>
			<div class="col-12">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
			</div>
		</div>

        <?php
        ActiveForm::end(); ?>

		<!--<div class="social-auth-links text-center mb-3">
			<p>- OR -</p>
			<a href="#" class="btn btn-block btn-primary">
				<i class="fab fa-facebook mr-2"></i> Sign in using Facebook
			</a>
			<a href="#" class="btn btn-block btn-danger">
				<i class="fab fa-google-plus mr-2"></i> Sign in using Google+
			</a>
		</div>-->
		<!-- /.social-auth-links -->

		<!--<p class="mb-1">
			<a href="forgot-password.html">I forgot my password</a>
		</p>
		<p class="mb-0">
			<a href="register.html" class="text-center">Register a new membership</a>
		</p>-->
	</div>
	<!-- /.login-card-body -->
</div>