<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Категории';
$this->params['breadcrumbs'][] = $this->title;

PluginAsset::register($this)->add(
    ['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']
);
?>
	<div class="category-index">

		<!--<h1><?
        /*= Html::encode($this->title) */ ?></h1>-->

		<p>
            <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-outline-success']) ?>
		</p>

        <?php
        // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'summary'      => '',
//                'filterModel'  => $searchModel,
                'filterModel'  => null,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered',
                    'id'    => 'category'
                ],
                'columns'      => [
//                    ['class' => 'yii\grid\SerialColumn'],
//                    'id',
                    'category_name',
                    [
                    		'class' => 'yii\grid\ActionColumn',
//						'template'=>'{view}{delete}'
					],
                ],
            ]
        ); ?>


	</div>

<?php
$js = <<< JS
 $(function () {
     
   /*$("#example2").DataTable({
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
    
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');*/
 
    $('#category').DataTable({
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


$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>