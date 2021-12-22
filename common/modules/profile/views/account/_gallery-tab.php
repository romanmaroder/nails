<?php
/* @var $this yii\web\View */

/* @var $model AccountController */

/* @var $models Photo */

use common\models\Photo;
use common\modules\profile\controllers\AccountController;
use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\widgets\ListView;

PluginAsset::register($this)->add(
    ['ekko-lightbox']
);

?>


<div class=" p-0 row">

    <?=
    ListView::widget(
        [
            'dataProvider' => $model,
            'options' => [
                'tag' => false,
                #'class' => 'col-12 col-lg-6 mb-3 list-wrapper',
                #'#id' => 'list-wrapper',
            ],
            'layout' => "{pager}\n{items}\n",
            'itemOptions' => ['tag' => null],
            'itemView' => function ($model, $key, $index) {
                $layout = '';
                $layout .= '<div class="filtr-item col-sm-2 " id="' . $model['id'] . '">
                            <a href="' . Yii::$app->storage->getFile($model['image'], 'php:d/m/Y') . '"
                                data-toggle="lightbox"
                                data-footer="' . Yii::$app->formatter->asDatetime($model['created_at'], 'php:d/m/Y') . '">
                            <div class="position-relative">
                                <img class="img-fluid mb-2"
                                     alt="' . $model['image'] . '"
                                     src="' . Yii::$app->storage->getFile($model['image']) . '">
                            </div>';

                if (!empty($model['user']['username'])) {
                    $layout .= '<div class="text-sm">' . $model['user']['username'] . '</div>';
                }
                $layout .= '<div class="d-flex justify-content-between">';

                if (Yii::$app->user->can('perm_view-calendar')) {
                    $layout .= '<span class=" photo-delete" >'
                        . Html::a(
                            '<i class="fas fa-trash" ></i > ',
                            [
                                '/profile/account/delete-photo',
                                'id' => $model['id'],
                                'class' =>
                                    get_class(new Photo())
                            ],
                            ['class' => 'delete-photo']
                        ) . '</span >';
                }
                $layout .= '<time class="time text-muted ml-auto">
                                <small class="d-block text-right px-2">
                                    ' . date('d/m/Y', $model['created_at']) . '
                                </small>
                            </time>
                        </div>
                        </a>
                    </div>';

                return $layout;

            },
            'emptyText' => 'Еще нет фото с Вашим дизайном',
            'emptyTextOptions' => [
                'tag' => 'div',
                'class' => 'col-12 col-lg-6 mb-3 text-info'
            ],

        ]
    );
    ?>

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
$('.delete-photo').on('click',function (e){
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
