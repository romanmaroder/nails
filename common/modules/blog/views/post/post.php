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
		<div class="col-md-10">
			<article class="post__inner px-md-3">
				<div class="post__header w-100">
					<h1 class="post__title mb-4"><?php
                        echo $post->title; ?></h1>
					<div class="post__meta d-flex justify-content-start position-relative mb-3">
						<div class="post__author text-muted mr-2 d-flex">
							<div class="text-lowercase mr-1">Автор: </div>
							<div class="post__author--name font-weight-bold">
                            	<?php
                                echo Html::a(
                                    $post->user->username,
//                                    ['/site/view', 'id' => $post->user_id],
                                    ['#', 'id' => $post->user_id],
                                    ['class' => 'post__author--link text-uppercase']
                                ); ?>
								<!-- Default box -->
									<div class="card-body pb-0 position-absolute ">
										<div class="row">
												<div class="col-12 col-sm-8 d-flex align-items-stretch
												flex-column">
													<div class="card bg-light d-flex flex-fill">
														<div class="card-header text-muted border-bottom-0">
														</div>
														<div class="card-body pt-0">
															<div class="row">
																<div class="col-5 text-center">
																	<img src="<?php
                                                                    echo $post->user->getPicture(); ?>"
																		 alt="user-avatar"
																		 class="img-circle img-fluid">
																</div>
																<div class="col-7">
																	<span class="lead"><b> <?php
                                                                            echo Html::a(
                                                                                $post->user->username,
                                                                                ['/site/view', 'id' => $post->user_id],
                                                                                ['class' => '']
                                                                            ); ?></b></span>
																	<?php
																	echo $post->user->getRoles('description') ?
																		'<p class="font-weight-light">('.implode(
																			', ',
																			$post->user->getRoles('description')
																		).')</p>' : '' ?>
																	<p class="font-weight-light"> <?php echo $post->user->profile->notes ;?></p>
																	<!--<p class="text-muted text-sm"><b>Обо мне: </b> <?php
																	/*                                            echo $item->description; */ ?></p>-->
																	<!--<ul class="ml-4 mb-0 fa-ul text-muted">
																		<li class="small mb-3"><span class="fa-li"><i class="fas fa-lg
																	fa-building"></i></span> <?php
/*																			echo $item->address ? $item->address : 'бомж'; */?></li>
																		<li class="small mb-3"><span class="fa-li"><i class="fas fa-lg
																	fa-phone"></i></span> <?php
/*																			echo $item->phone ? Html::a(
																				$item->phone,
																				'tel:'.$item->phone
																			) : 'нет номера'; */?></li>
																		<li class="small mb-3"><span class="fa-li"><i class="fas
																	fa-birthday-cake"></i></span>
																			<?php
/*																			echo $item->birthday
																				? Yii::$app->formatter->asDate(
																					$item->birthday,
																					'php:d-m-Y'
																				) : 'еще не родился'; */?>
																		</li>
																		<li class="small"><span class="fa-li"><i
																						class="fas fa-paint-brush"></i></span>
																			<?php
/*																			$option = [
																				'style' => [
																					'width'            => '20px',
																					'height'           => '20px',
																					'border-radius'    => '20px',
																					'background-color' => $item->color
																				]
																			];
																			echo Html::tag('div', '', $option); */?>
																		</li>
																	</ul>-->
																</div>

															</div>
														</div>
														<!--<div class="card-footer">
															<div class="text-right">
																<?/*= Html::a(
																	'<i class="fas fa-user"></i> Подробнее...',
																	['view', 'id' => $post->user_id],
																	['class' => 'btn btn-sm btn-primary']
																) */?>
															</div>
														</div>-->
													</div>
												</div>
										</div>
									</div>
								<!-- /.card-body -->
								<!-- /.card -->
							</div>
						</div>
						<span class="post__publish text-muted text-uppercase"> Опубликовано <?php
                            echo Yii::$app->formatter->asDate
                            (
                                $post->created_at
                            );
                            ?></span>
					</div>
				</div>
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
        <div class="col-md-2 d-none d-md-block">
            <?php
            echo NewsList::widget(['showLimit' => 8]); ?>
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
	}
	fillProgressLine();
   });
JS;

$this->registerJs($progressBar, $position = yii\web\View::POS_READY, $key = null);
?>