<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

PluginAsset::register($this)->add(
    ['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons','sweetalert2']
);


$this->title                   = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;
?>


	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h3 class="card-title"><?php
                                echo $this->title; ?></h3>
						</div>
						<!-- /.card-header -->
						<div class="card-body">

                            <?php if( Yii::$app->session->hasFlash('info') ): ?>
                                <div class="alert alert-info alert-dismissible mt-3" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <?php echo Yii::$app->session->getFlash('info'); ?>
                                </div>
                            <?php endif;?>


                            <?php
                            if (Yii::$app->id =='app-frontend') : ?>
                                <?= GridView::widget(
                                    [
                                        'dataProvider' => $dataProvider,
                                        'summary'      => '',
                                        'tableOptions' => [
                                            'class' => 'table table-striped table-bordered',
                                            'id'    => 'example2'
                                        ],
                                        'columns'      => [
//            ['class' => 'yii\grid\SerialColumn'],

                                            'username',

                                            [
                                                'attribute' => 'gallery',
                                                'label'     => 'Дизайн',
                                                'format'    => 'raw',
                                                'value'     => function ($model) {
                                                    return Html::a(
                                                        'Дизайн',
                                                        [
                                                            '/profile/account/gallery',
                                                            'id' => $model->id
                                                        ]
                                                    );
                                                }
                                            ],
                                            [
                                                'attribute' => 'status',
                                                'format'    => 'raw',
                                                'value'     => function ($model) {
                                                    return $model->getStatusUser($model->status);
                                                },
                                            ],
                                           /* [
                                                'attribute' => 'color',
                                                'format'    => 'raw',
                                                //'visible'   => Yii::$app->user->can('perm_view-calendar'),
                                                'value'     => function ($model) {
                                                    $option = [
                                                        'style' => [
                                                            'width'            => '100%',
                                                            'height'           => '20px',
                                                            'border-radius'    => '20px',
                                                            'margin'           => '0 auto',
                                                            'background-color' => $model->color
                                                        ]
                                                    ];
                                                    return $model->color ? Html::tag('div', '', $option) : Html::tag(
                                                        'div',
                                                        '-',
                                                        $option
                                                    );
                                                },
                                            ],*/
                                            [
                                                'attribute' => 'avatar',
                                                'format'    => 'raw',
                                                'value'     => function ($model) {
                                                    return '<div class="image" style="width:100px">
														<img class="img-circle"
															 style="width: 100%;height: auto"
															 src="'.$model->getPicture().'"
															 alt="'.$model->username.'"
															 title = "'.$model->username.'">
														</div>';
                                                },
                                            ],
                                            'description:ntext',
                                            [
                                                'attribute' => 'birthday',
                                                'format'    => ['date', 'php:d-m-Y'],
                                                'value'     => function ($model) {
                                                    return $model->birthday ?: null;
                                                }
                                            ],
                                            [
                                                'attribute' => 'phone',
                                                'format'    => 'raw',
                                                'value'     => function ($phone) {
                                                    return $phone->phone ? Html::a(
                                                        $phone->phone,
                                                        'tel:'.$phone->phone
                                                    ) : 'номер не указан';
                                                }
                                            ],
                                            'address',
                                            [
                                                'class'          => 'yii\grid\ActionColumn',
                                                'template' => '{view}'
                                            ],
                                        ],
                                    ]
                                ); ?>
                            <?php
                            else: ?>
                                <?= GridView::widget(
                                    [
                                        'dataProvider' => $dataProvider,
                                        'summary'      => '',
                                        'tableOptions' => [
                                            'class' => 'table table-striped table-bordered',
                                            'id'    => 'example2'
                                        ],
                                        'columns'      => [
//            ['class' => 'yii\grid\SerialColumn'],

                                            'username',
                                           /* [
                                                'attribute' => 'roles',
//                                            'format'    => 'raw',
                                                'value'     => function ($roles) {
                                                    $role = array_values(
                                                        Yii::$app->authManager->getRolesByUser
                                                        (
                                                            $roles->id
                                                        )
                                                    )[0];
                                                    return $role->description;
                                                }
                                            ],*/
//                                        'email:email',
                                            [
                                                'attribute' => 'gallery',
                                                'label'     => 'Дизайн',
                                                'format'    => 'raw',
                                                'value'     => function ($model) {
                                                    return Html::a(
                                                        'Дизайн',
                                                        [
                                                            '/profile/account/gallery',
                                                            'id' => $model->id
                                                        ]
                                                    );
                                                }
                                            ],
                                            [
                                                'attribute' => 'status',
                                                'format'    => 'raw',
                                                'value'     => function ($model) {
                                                    return $model->getStatusUser($model->status);
                                                },
                                            ],
                                          /*  [
                                                'attribute' => 'color',
                                                'format'    => 'raw',
                                                //'visible'   => Yii::$app->user->can('perm_view-calendar'),
                                                'value'     => function ($model) {
                                                    $option = [
                                                        'style' => [
                                                            'width'            => '100%',
                                                            'height'           => '20px',
                                                            'border-radius'    => '20px',
                                                            'margin'           => '0 auto',
                                                            'background-color' => $model->color
                                                        ]
                                                    ];
                                                    return $model->color ? Html::tag('div', '', $option) : Html::tag(
                                                        'div',
                                                        '-',
                                                        $option
                                                    );
                                                },
                                            ],*/
                                            [
                                                'attribute' => 'avatar',
                                                'format'    => 'raw',
                                                'value'     => function ($model) {
                                                    return '<div class="image" style="width:100px">
														<img class="img-circle"
															 style="width: 100%;height: auto"
															 src="'.$model->getPicture().'" 
															 alt="'.$model->username.'"
															 title = "'.$model->username.'">
														</div>';
                                                },
                                            ],
                                            'description:ntext',
                                            [
                                                'attribute' => 'birthday',
                                                'format'    => ['date', 'php:d-m-Y'],
                                                'value'     => function ($model) {
                                                    return $model->birthday ?: null;
                                                }
                                            ],
                                            [
                                                'attribute' => 'phone',
                                                'format'    => 'raw',
                                                'value'     => function ($phone) {
                                                    return $phone->phone ? Html::a(
                                                        $phone->phone,
                                                        'tel:'.$phone->phone
                                                    ) : 'номер не указан';
                                                }
                                            ],
                                            'address',
                                            [
                                                'class'          => 'yii\grid\ActionColumn',
                                                'visibleButtons' => [
                                                    'update' => function () {
                                                        return Yii::$app->user->can('manager');
                                                    },
                                                    'delete' => function () {
                                                        return Yii::$app->user->can('manager');
                                                    },
                                                ]
                                            ],
                                        ],
                                    ]
                                ); ?>
                            <?php
                            endif; ?>
						</div>
						<!-- /.card-body -->
					</div>
					<!-- /.card -->
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- /.content -->

<?php
#Регистрация переменных для использования в js коде

Yii::$app->view->registerJs(
    "create=".Json::encode(Url::to(['/client/client/create'])).";
     ",
    View::POS_HEAD
); ?>

<?php

if (Yii::$app->id == 'app-backend') {
    $js = <<< JS
 $(function () {
   $("#example2").DataTable({
      "responsive": true,
      "lengthChange":true,
      "pageLength": 10,
      "autoWidth": false,
      "info": false,
      "dom": "<'row'<'col-sm-12 col-md-4 order-3 order-md-1 text-left'B><'col-sm-12 col-md-4 order-md-3 text-md-right d-flex d-md-block'l><'col-sm-12 order-md-2 col-md-4 d-flex d-md-block'f>>tp",
      "buttons": [
        {
				"text": "Добавить клиента",
				"className":"btn btn-success",
				"tag":"a",
				"attr":{
				"href":create
				},
				"action": function ( e, dt, node, config ) {
				  $(location).attr('href',config.attr.href);
				}
        }
        ],
         "language": {
           "lengthMenu": 'Показать <select class="form-control form-control-sm">'+
      '<option value="10">10</option>'+
      '<option value="20">20</option>'+
      '<option value="50">50</option>'+
      '<option value="-1">Все</option>'+
      '</select>',
          "search":"Поиск",
          "zeroRecords": "Совпадений не найдено",
    	  "emptyTable": "В таблице отсутствуют данные",
          "paginate": {
                    "first": "Первая",
                    "previous": '<i class="fas fa-backward"></i>',
                    "last": "Последняя",
                    "next": '<i class="fas fa-forward"></i>'
                }
         }
    
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)')
    
  });
JS;
} else {
    $js = <<< JS
 $(function () {
     
   $("#example2").DataTable({ 
      "responsive": true,
      "autoWidth": false,
      "info": false,
      "lengthChange":true,
      "pageLength": 10,
        "dom": "<'row'<'col-12 col-sm-6 d-flex align-content-md-start'f><'col-12 col-sm-6 d-flex justify-content-sm-end'l>>tp",
        "buttons": [
        ],
         "language": {
          "lengthMenu": 'Показать <select class="form-control form-control-sm">'+
      '<option value="10">10</option>'+
      '<option value="20">20</option>'+
      '<option value="50">50</option>'+
      '<option value="-1">Все</option>'+
      '</select>',
          "search":"Поиск",
          "paginate": {
                    "first": "Первая",
                    "previous": '<i class="fas fa-backward"></i>',
                    "last": "Последняя",
                    "next": '<i class="fas fa-forward"></i>'
                }
         }
    
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
 
  });
JS;
}


$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>