<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;


PluginAsset::register($this)->add(
    ['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons', 'sweetalert2']
);
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="category-index">
                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'summary'      => '',
                            'filterModel'  => null,
                            'tableOptions' => [
                                'class' => 'table table-striped table-bordered',
                                'id'    => 'category'
                            ],
                            'columns'      => [
                                'category_name',
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                ],
                            ],
                        ]
                    ); ?>

                </div>
            </div>
        </div>
    </div>
<?php
#Регистрация переменных для использования в js коде

Yii::$app->view->registerJs(
    "create=".Json::encode(Url::to(['/category/create'])).";
     ",
    View::POS_HEAD
); ?>

<?php
$js = <<< JS
 $(function () {
 
    $('#category').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false,
      "responsive": true,
         "dom": "<'row'<'col-6 col-md-6 order-3 order-md-1 text-left'B><'col-sm-12 order-md-2 col-md-6 d-flex d-md-block'f>>tp",
      "buttons": [
        {
				"text": "Добавить категорию",
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
          "search":"Поиск"
         },
    }).buttons().container().appendTo('#category_wrapper .col-md-6:eq(0)');
  });
JS;


$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>