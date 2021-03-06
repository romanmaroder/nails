<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

PluginAsset::register($this)->add(['sweetalert2']);
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="post-view">
                    <p>
                        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?php
                        if (Yii::$app->user->can('manager')) : ?>
                            <?= Html::a(
                                $model->status ? 'Снять с публикации' : 'Опубликовать',
                                ['publish', 'id' => $model->id],
                                [
                                    'class'       => 'btn btn-primary',
                                    'id'          => 'published',
                                    'data-status' => $model->status
                                ]
                            ) ?>
                        <?php
                        endif; ?>

                        <?= Html::a(
                            'Удалить',
                            ['delete', 'id' => $model->id],
                            [
                                'class' => 'btn btn-danger my-2 my-md-0',
                                'data'  => [
                                    'confirm' => 'Удалить статью?',
                                    'method'  => 'post',
                                ],
                            ]
                        ) ?>
                    </p>

                    <?= DetailView::widget(
                        [
                            'model'      => $model,
                            'attributes' => [
                                [
                                    'attribute' => 'user_id',
                                    'value'     => function ($model) {
                                        return $model->user->username;
                                    }
                                ],
                                [
                                    'attribute' => 'category_id',
                                    'value'     => function ($model) {
                                        return $model->category->category_name;
                                    }
                                ],
                                'slug',
                                'title',
                                'subtitle',
                                [
                                    'attribute' => 'description',
                                    'format'    => 'raw',

                                ],
                                [
                                    'attribute' => 'preview',
                                    'format'    => 'raw',
                                    //'value'=>Yii::$app->storage->getFile($model->preview),
                                    'value'     => function ($model) {
                                        $option = [
                                            'style' => [
                                                'width'  => '200px',
                                                'height' => 'auto',
                                            ]
                                        ];
                                        return Yii::$app->storage->getFile($model->preview) ? Html::tag
                                        (
                                            'div',
                                            Html::img(
                                                Yii::$app->storage->getFile($model->preview),
                                                [
                                                    'alt'   => 'Превью статьи',
                                                    'style' => 'max-width:100%; height:auto
																  '
                                                ]
                                            ),
                                            $option
                                        ) : 'Превью не выбрано';
                                    },

                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format'    => ['date', 'php:d-m-Y H:i']
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'format'    => ['date', 'php:d-m-Y H:i']
                                ],
                            ],
                        ]
                    ) ?>

                </div>
            </div>
        </div>
    </div>
<?php
$js = <<< JS
$('#published').on('click',function (e){
    e.preventDefault();
    var path = $(this).attr('href');
    var status =$(this).attr('data-status');
    console.log(status);
    $.ajax({
     	type: "POST",
    	url: path,
    	success: function(msg){ 
     	    if (msg.success === false){
     	         var Toast = Swal.mixin({
						  toast: true,
						  position: "top-end",
						  showConfirmButton: false,
						  timer: 5000,
						  didOpen:()=>{
						      $('#published').text(msg.status)
						  }
						});
						  Toast.fire({
							icon: "error",
							title: msg.message
						  });
     	    }else {
     	          Toast = Swal.mixin({
						  toast: true,
						  position: "top-end",
						  showConfirmButton: false,
						  timer: 5000,
						  didOpen:()=>{
						      $('#published').text(msg.status)
						  }
						});
						  Toast.fire({
							icon: "success",
							title: msg.message
						  });
     	    }
     	   
						  
    	},
    	error: function (error){
     	    alert(error);
    	}
    })
})

JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);
?>