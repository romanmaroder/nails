<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

PluginAsset::register($this)->add(
    ['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']
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
                                        ],
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
//                                        'color',
                                        [
                                            'attribute' => 'color',
                                            'format'    => 'raw',
                                            'visible'   => Yii::$app->user->can('perm_view-calendar'),
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
                                        ],
                                        [
                                            'attribute' => 'avatar',
                                            'format'    => 'raw',
                                            'value'     => function ($model) {
                                                return '<div class="image" style="width:100px">
														<img class="img-circle"
															 style="width: 100%;height: auto"
															 src="'.$model->getPicture().'" alt="">
														</div>';
                                            },
                                        ],
                                        'description:ntext',
//                                        'birthday',
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
                                        /*[
                                            'attribute' => 'created_at',
                                            'format'    => ['date', 'php:d-m-Y'],
                                        ],*/

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

if (Yii::$app->id == 'app-backend') {
    $js = <<< JS
 $(function () {
     
   $("#example2").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "info": false,
      buttons: [
        {
				"text": "Добавить клиента",
				"className":"btn btn-success",
				"tag":"a",
				"attr":{
				"href":"/admin/client/client/create"
				},
				"action": function ( e, dt, node, config ) {
				  $(location).attr('href',config.attr.href);
				}
        }
        ],
         "language": {
          "search":"Поиск",
          "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "last": "Последняя",
                    "next": "Следующая"
                }
         }
    
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
 
    /*$('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });*/
  });
JS;
} else {
    $js = <<< JS
 $(function () {
     
   $("#example2").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "info": false,
       /*"order": [[ "role", "desc" ]],*/
         "language": {
          "search":"Поиск",
          "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "last": "Последняя",
                    "next": "Следующая"
                }
         }
    
    });
 
  });
JS;
}


$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>