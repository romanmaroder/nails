<?php
/* @var $this yii\web\View */

/* @var $certificateList \common\modules\profile\controllers\AccountController */
/* @var $models \common\models\Certificate */

use common\models\Certificate;
use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;

PluginAsset::register($this)->add(
    ['ekko-lightbox']
);

$models = new Certificate();
$class = get_class($models);
?>

<?php /*echo '<pre>';
var_dump($certificateList);
die();
*/?>

<div class=" p-0 row">
	<?php if ($certificateList) :?>

    <?php
    foreach ($certificateList as $item) : ?>
        <div class="filtr-item col-sm-2 "
             id="<?php
             echo $item['id']; ?>">
            <a href="<?php
            echo Yii::$app->storage->getFile($item['certificate'], 'php:d/m/Y'); ?>"
               data-toggle="lightbox"
               data-gallery="mixedgallery" >
                <!--												TODO Дата - разобраться-->
                <div class="position-relative">
                    <img class="img-fluid mb-2"
                         alt="<?php
                         echo $item['certificate']; ?>"
                         src="<?php
                         echo Yii::$app->storage->getFile($item['certificate']); ?>">
                    <?php if (!empty($item['user']['username'])) :?>
                    <?php endif ;?>
                </div>
                <div class="d-flex justify-content-between">
                    <?php if (Yii::$app->user->can('perm_view-calendar')) :?>
                        <span class=" photo-delete">
							<?php
                            echo Html::a(
                                '<i class="fas fa-trash"></i>',
                                ['/profile/account/delete-photo', 'id' => $item['id'],'class'=>$class],
                                ['id' => 'delete-photo']
                            ); ?>
						</span>
                    <?php endif ;?>
                </div>
            </a>
        </div>
    <?php
    endforeach; ?>
	<?php else: ?>
	<div class="col-12">Вы еще не добавили сертификаты</div>
    <?php endif ;?>
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
$('body').on('click','#delete-photo',function (e){
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

