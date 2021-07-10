<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <p>
        <?= Html::a('Новая статья', ['create'], ['class' => 'btn btn-outline-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary'      => '',
//        'filterModel' => $searchModel,
        'filterModel' => null,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id'    => 'post'
        ],
        'columns' => [
           // ['class' => 'yii\grid\SerialColumn'],
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
            'title',
            'subtitle',
            /*[
                'attribute' => 'description',
                'format'    => 'html',
            ],*/
            [
                'attribute' => 'status',
				'format' => 'raw',
                'value'    => function ($model) {
                    return $model->status ? '<span class="text-success">опубликовано</span>' : '<span class="text-danger">не опубликовано</span>';
                },
            ],
            //'description:ntext',
            //'created_at',
			[
					'attribute' => 'created_at',
				'format' =>['date','php: d-m-Y']
			],
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

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


$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>