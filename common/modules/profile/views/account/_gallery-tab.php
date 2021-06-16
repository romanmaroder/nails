<?php
/* @var $this yii\web\View */

/* @var $model \common\modules\profile\controllers\AccountController */

use hail812\adminlte3\assets\PluginAsset;

PluginAsset::register($this)->add(
    ['ekko-lightbox', 'filterizr']
);


/*echo '<pre>';
var_dump($model);
die();*/

?>


	<div class=" p-0 row">
        <?php
        foreach ($model as $item) : ?>
			<div class="filtr-item col-sm-2 "
				 data-category="1"
				 data-sort="white sample">
				<a href="<?php
                echo Yii::$app->storage->getFile($item['image'], 'php:d/m/Y'); ?>"
				   data-toggle="lightbox"
				   data-gallery="mixedgallery"
				   data-footer="<?php
                   echo Yii::$app->formatter->asDatetime($item['created_at'], 'php:d/m/Y');
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
                                echo $item['user']['username']; ?>
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