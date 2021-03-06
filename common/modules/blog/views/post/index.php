<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;

PluginAsset::register($this)->add(
    ['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons', 'sweetalert2']
);
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="post-index">
                    <?php
                    if ($dataProvider->getCount() === 0) {
                        echo 'Вы пока не добавили статьи';
                    } else {
                        echo GridView::widget(
                            [
                                'dataProvider' => $dataProvider,
                                'summary'      => '',
                                //'filterModel' => $searchModel,
                                'filterModel'  => null,
                                'tableOptions' => [
                                    'class'     => 'table table-striped table-bordered',
                                    'id'        => 'post',
                                    'data-pjax' => 0
                                ],
                                'columns'      => [
                                    [
                                        'attribute' => 'user_id',
                                        'value'     => function ($model) {
                                            return $model->user->username;
                                        },
                                        'visible'   => Yii::$app->user->can('manager')
                                    ],
                                    [
                                        'attribute' => 'category_id',
                                        'value'     => function ($model) {
                                            return $model->category->category_name;
                                        }
                                    ],
                                    'title',
                                    'subtitle',
                                    [
                                        'attribute' => 'status',
                                        'format'    => 'raw',
                                        'value'     => function ($model) {
                                            return $model->status ? '<span class="text-success">опубликовано</span>' : '<span class="text-danger">не опубликовано</span>';
                                        },
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format'    => ['date', 'php: d-m-Y']
                                    ],
                                    [
                                        'class'          => 'yii\grid\ActionColumn',
                                        'visibleButtons' => [
                                            'delete' => function () {
                                                return Yii::$app->user->can('manager');
                                            },
                                        ]
                                    ],
                                ],
                            ]
                        );
                    } ?>


                </div>
            </div>
        </div>
    </div>
<?php
if (Yii::$app->id == 'app-backend') {
    $js = <<< JS
 $(function () {
    $('#post').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": true,
       "dom": "<'row'<'col-6 col-sm-6 col-md-6  text-left'B><'col-12 col-sm-6 col-md-6 d-flex d-md-block'f>>tp",
      "buttons": [
        {
				"text": "Новая статья",
				"className":"btn btn-success",
				"tag":"a",
				"attr":{
				"href":"/admin/blog/post/create"
				},
				"action": function ( e, dt, node, config ) {
				  $(location).attr('href',config.attr.href);
				}
        }
        ],
      "language": {
          "search":"Поиск"
         
         }
    });

  });
JS;
} else {
    $js = <<< JS
 $(function () {
    $('#post').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
      "responsive": true,
      "language": {
          "search":"Поиск"
         }
    });

  });
JS;
}
$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);
?>