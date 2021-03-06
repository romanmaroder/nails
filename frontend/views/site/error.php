<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>



<!-- Main content -->
<section class="content">
	<div class="error-page">
		<h2 class="headline text-danger"> <?= Html::encode($this->title) ?></h2>

		<div class="error-content mt-3">
			<h3><i class="fas fa-exclamation-triangle text-danger"></i><?= nl2br(Html::encode($message)) ?></h3>

			<p>
				Вышеупомянутая ошибка произошла во время обработки вашего запроса веб-сервером.
			</p>
			<p>
				Свяжитесь с нами, если вы считаете, что это ошибка сервера. Спасибо.
			</p>


		</div>
		<!-- /.error-content -->
	</div>
	<!-- /.error-page -->
</section>
<!-- /.content -->

