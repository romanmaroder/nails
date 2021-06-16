<?php
/* @var $this yii\web\View */

/* @var $model \common\modules\profile\controllers\AccountController */

use hail812\adminlte3\assets\PluginAsset;

PluginAsset::register($this)->add(
    ['ekko-lightbox', 'filterizr']
);

$this->title                   = 'Галерея';
$this->params['breadcrumbs'][] = ['label' => 'галерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/*echo '<pre>';
var_dump($model);
die();*/

?>

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card card-primary card-outline">
						<div class="card-header">
							<h4 class="card-title">Галерея</h4>
						</div>
						<div class="card-body">
							<div class="row mb-3">
								<!--<div class="btn-group w-100 mb-2">
									<a class="btn btn-info active" href="javascript:void(0)" data-filter="all"> All
										items </a>
									<a class="btn btn-info" href="javascript:void(0)" data-filter="1"> Category 1
										(WHITE) </a>
									<a class="btn btn-info" href="javascript:void(0)" data-filter="2"> Category 2
										(BLACK) </a>
									<a class="btn btn-info" href="javascript:void(0)" data-filter="3"> Category 3
										(COLORED) </a>
									<a class="btn btn-info" href="javascript:void(0)" data-filter="4"> Category 4
										(COLORED, BLACK) </a>
								</div>-->
								<div class="col-12 col-md-2 mb-2 mb-md-0">
									<a class="btn btn-secondary" href="javascript:void(0)" data-shuffle> Перемешать </a>
								</div>
								<div class="col-12 col-lg-10 ">
									<div class="btn-group " data-sortOrder>
										<a class="btn btn-default" href="javascript:void(0)" data-sortAsc>
											По возрастанию </a>
										<a class="btn btn-default" href="javascript:void(0)" data-sortDesc>
											По убыванию </a>
									</div>
								</div>

							</div>
							<div>
								<div class="filter-container p-0 row">
                                    <?php
                                    foreach ($model as $item) : ?>
										<div class="filtr-item col-sm-2 "
											 data-category="1"
											 data-sort="white sample">
											<a href="<?php
                                            echo Yii::$app->storage->getFile($item['image'],'php:d/m/Y'); ?>"
											   data-toggle="lightbox"
											   data-gallery="mixedgallery"
											   data-footer="<?php
                                            echo Yii::$app->formatter->asDatetime( $item['created_at'],'php:d/m/Y');
                                            ?>">
<!--												TODO Дата - разобраться-->
												<div class="position-relative">
													<img class="img-fluid mb-2"
														 alt="<?php
                                                         echo $item['image']; ?>"
														 src="<?php
                                                         echo Yii::$app->storage->getFile($item['image']); ?>">
													<div class="ribbon-wrapper ribbon-sm">

														<div class="ribbon bg-success text-sm">
                                                            <?php
                                                            echo $item['master']['username']; ?>
														</div>

													</div>
												</div>
												<time class="time text-muted">
													<small class="d-block text-right px-2">
                                                        <?php
                                                        echo date('d/m/Y', $item['created_at']); ?>
													</small>
												</time>
											</a>
										</div>
                                    <?php
                                    endforeach; ?>
								</div>
							</div>

						</div>
					</div>
				</div>
				<!--<div class="col-12">
					<div class="card card-primary">
						<div class="card-header">
							<h4 class="card-title">Ekko Lightbox</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/FFFFFF.png?text=1"
									   data-toggle="lightbox" data-title="sample 1 - white" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/FFFFFF?text=1"
											 class="img-fluid mb-2" alt="white sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/000000.png?text=2"
									   data-toggle="lightbox" data-title="sample 2 - black" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/000000?text=2"
											 class="img-fluid mb-2" alt="black sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/FF0000/FFFFFF.png?text=3"
									   data-toggle="lightbox" data-title="sample 3 - red" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/FF0000/FFFFFF?text=3"
											 class="img-fluid mb-2" alt="red sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/FF0000/FFFFFF.png?text=4"
									   data-toggle="lightbox" data-title="sample 4 - red" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/FF0000/FFFFFF?text=4"
											 class="img-fluid mb-2" alt="red sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/000000.png?text=5"
									   data-toggle="lightbox" data-title="sample 5 - black" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/000000?text=5"
											 class="img-fluid mb-2" alt="black sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/FFFFFF.png?text=6"
									   data-toggle="lightbox" data-title="sample 6 - white" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/FFFFFF?text=6"
											 class="img-fluid mb-2" alt="white sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/FFFFFF.png?text=7"
									   data-toggle="lightbox" data-title="sample 7 - white" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/FFFFFF?text=7"
											 class="img-fluid mb-2" alt="white sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/000000.png?text=8"
									   data-toggle="lightbox" data-title="sample 8 - black" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/000000?text=8"
											 class="img-fluid mb-2" alt="black sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/FF0000/FFFFFF.png?text=9"
									   data-toggle="lightbox" data-title="sample 9 - red" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/FF0000/FFFFFF?text=9"
											 class="img-fluid mb-2" alt="red sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/FFFFFF.png?text=10"
									   data-toggle="lightbox" data-title="sample 10 - white" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/FFFFFF?text=10"
											 class="img-fluid mb-2" alt="white sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/FFFFFF.png?text=11"
									   data-toggle="lightbox" data-title="sample 11 - white" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/FFFFFF?text=11"
											 class="img-fluid mb-2" alt="white sample"/>
									</a>
								</div>
								<div class="col-sm-2">
									<a href="https://via.placeholder.com/1200/000000.png?text=12"
									   data-toggle="lightbox" data-title="sample 12 - black" data-gallery="gallery">
										<img src="https://via.placeholder.com/300/000000?text=12"
											 class="img-fluid mb-2" alt="black sample"/>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>-->
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->


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