<?php

/* @var $post \common\modules\blog\controllers\PostController */

use common\widgets\newsList\NewsList;
use yii\helpers\Html;
use yii\bootstrap4\Progress;

$this->title = $post->title;

?>

	<div class="row sticky-top">
		<div class="col-12">
            <?php
            echo Progress::widget(
                [
                    'percent'    => 0,
                    'barOptions' => ['class' => 'progress-bar progress-bar-danger progress-bar-striped'],
                    'options'    => ['class' => 'progress progress-xxs', 'id' => 'progress-bar']
                ]

            ); ?>
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-md-3 d-none d-md-block">
            <?php
            echo NewsList::widget(['showLimit' => 8]); ?>
		</div>
		<div class="col-md-9">
			<article class="post__inner px-md-3">
				<h1 class="post__title mb-4"> <?php
                    echo $post->title; ?></h1>
				<div class="post__description"><?php
                    echo $post->description; ?></div>
				<p class="read-more align-self-end"><?php
                    echo Html::a(
                        '<i class="fas fa-arrow-left"></i> Назад ',
                        Yii::$app->request->referrer,
                        ['class' => 'mt-3']
                    ); ?></p>
			</article>

		</div>
	</div>


<?php
$progressBar = <<< JS
$(function () {
    let fullHeight, innerHeight;
	const progressBar = $('#progress-bar>.progress-bar-striped');

	$(window).scroll(fillProgressLine);
	$(window).resize(fillProgressLine);

	function fillProgressLine() {
		fullHeight = $(document.body).prop('scrollHeight');
		innerHeight = $(window).prop('innerHeight');
		progressBar.css({"width" : (pageYOffset * 100 / (fullHeight - innerHeight)) + '%'});
		let a =pageYOffset * 100 / (fullHeight - innerHeight);
		if (a.toFixed() == 50){alert('Половина')}
		console.log(a.toFixed());
	}
	fillProgressLine();
   });
JS;

$this->registerJs($progressBar, $position = yii\web\View::POS_READY, $key = null);
?>