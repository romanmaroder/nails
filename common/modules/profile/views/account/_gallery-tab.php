<?php
/* @var $this yii\web\View */

/* @var $model AccountController */

/* @var $models Photo */

use common\models\Photo;
use common\modules\profile\controllers\AccountController;
use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;

PluginAsset::register($this)->add(
    ['ekko-lightbox']
);

?>


<div class=" p-0 row">
    <?php
    foreach ($model as $item) : ?>
        <div class="filtr-item col-sm-2 "
             id="<?php
             echo $item['id']; ?>">
            <a href="<?php
            echo Yii::$app->storage->getFile($item['image'], 'php:d/m/Y'); ?>"
               data-toggle="lightbox"
               data-gallery="mixedgallery"
               data-footer="<?php
               echo Yii::$app->formatter->asDatetime($item['created_at'], 'php:d/m/Y');
               ?>">
                <div class="position-relative">
                    <img class="img-fluid mb-2"
                         alt="<?php
                         echo $item['image']; ?>"
                         src="<?php
                         echo Yii::$app->storage->getFile($item['image']); ?>">
                </div>
                <?php if (!empty($item['user']['username'])) : ?>
                    <div class="text-sm"><?php
                        echo $item['user']['username']; ?></div>
                <?php endif; ?>
                <div class="d-flex justify-content-between">
                    <?php if (Yii::$app->user->can('perm_view-calendar')) : ?>
                        <span class=" photo-delete">
							<?php
                            echo Html::a(
                                '<i class="fas fa-trash"></i>',
                                ['/profile/account/delete-photo', 'id' => $item['id'], 'class' =>
                                    get_class(new Photo())],
                                ['id' => 'delete-photo']
                            ); ?>
						</span>
                    <?php endif; ?>
                    <time class="time text-muted ml-auto">
                        <small class="d-block text-right px-2">
                            <?php
                            echo date('d/m/Y', $item['created_at']); ?>
                        </small>
                    </time>
                </div>
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
        })
JS;
$this->registerJs($gallery, $position = yii\web\View::POS_READY, $key = null); ?>

<?php

$js = <<< JS
$('#delete-photo').on('click',function (e){
    e.preventDefault();
    var path = $(this).attr('href');
    var image= $(this).closest('.filtr-item')
    $.ajax({
     	type: "POST",
    	 url: path,
    	success: function(msg){ 
     	    
     	    var Toast = Swal.mixin({
						  toast: true,
						  position: "top-end",
						  showConfirmButton: false,
						  timer: 5000,
						  didOpen:()=>{
						     image.remove();
						  }
						 
						});
						  Toast.fire({
							icon: "error",
							title: msg.message
						  });
						  
    	},
    	error: function (error){
     	    alert(error);
    	}
    })
})

JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);
?>
