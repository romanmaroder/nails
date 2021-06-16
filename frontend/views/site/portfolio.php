<?php

/* @var $this yii\web\View */

/* @var $portfolio \frontend\controllers\SiteController */

use hail812\adminlte3\assets\PluginAsset;

PluginAsset::register($this)->add(
    ['ekko-lightbox', 'filterizr']
);


$this->title                   = 'Портфолио';
$this->params['breadcrumbs'][] = $this->title;

/*echo '<pre>';
var_dump($portfolio);*/
?>

	<section class="content">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col-12">
					<div class="card card-outline card-dark">
						<div class="card-header">
							<h4 class="card-title">Портфолио</h4>
						</div>
						<div class="card-body">
							<div>
								<div class="btn-group mb-2">
									<a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> Все
										мастера </a>
                                    <?php
                                    $user_id = [];
                                    foreach ($portfolio as $user_unique) {
                                        if (in_array($user_unique->user_id, $user_id)) {
                                            continue;
                                        }
                                        $user_id[] = $user_unique->user_id;
                                        echo '<a class="btn btn-info" href="javascript:void(0)" data-filter="'
                                            .$user_unique->user_id.'">'.$user_unique->master->username.' </a>';
                                    }?>
								</div>
								<div class="mb-3">
									<a class="btn btn-secondary mb-2" href="javascript:void(0)" data-shuffle>Перемешать
									</a>
									<div class="float-md-right" data-sortOrder>
										<div class="btn-group">
											<a class="btn btn-default" href="javascript:void(0)" data-sortAsc>
												По возрастанию </a>
											<a class="btn btn-default" href="javascript:void(0)" data-sortDesc>
												По убыванию </a>
										</div>
									</div>
								</div>
							</div>
							<div>
								<div class="filter-container p-0 row">
                                    <?php
                                    foreach ($portfolio as $key => $photo) : ?>
										<div class="filtr-item col-sm-2 mb-3" data-category="<?php
                                        echo $photo->user_id; ?>"
											 data-sort="<?php
                                             echo $photo->user_id; ?>">
											<a href="<?php
                                            echo Yii::$app->storage->getFile($photo['image']); ?>"
											   data-toggle="lightbox"
											   data-gallery="example-gallery"
											   class="col-sm-4 mb-3">
												<img src="<?php
                                                echo Yii::$app->storage->getFile($photo['image']); ?>"
													 class="img-fluid img-square" alt="portfolio-image">
											</a>
										</div>
                                    <?php
                                    endforeach; ?>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php
$gallery = <<<JS
		$(function () {
		$(document).on('click', '[data-toggle="lightbox"]', function(event) {
event.preventDefault();
$(this).ekkoLightbox({
alwaysShowClose: false
});
});

$('.filter-container').filterizr({gutterPixels: 3});
$('.btn[data-filter]').on('click', function() {
$('.btn[data-filter]').removeClass('active');
$(this).addClass('active');
});
})
JS;
$this->registerJs($gallery, $position = yii\web\View::POS_READY, $key = null); ?>