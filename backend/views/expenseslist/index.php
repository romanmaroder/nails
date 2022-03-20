<?php

use common\components\totalCell\NumberColumn;
use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ExpenseslistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Затраты';
$this->params['breadcrumbs'][] = $this->title;
PluginAsset::register($this)->add(
    ['sweetalert2','datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']
);
?>
<div class="expenseslist-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить затраты', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'showFooter'   => true,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered text-center',
            'id'=>'expensesList'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'expenses_id',
                'contentOptions' => [
                    'class' => 'text-left'
                ],
                'value'     => function ($model) {
                    return $model->expenses->title;
                }
            ],

            [
                'class'         => NumberColumn::class,
                'attribute'=> 'price',
                'footerOptions' => ['class' => 'bg-success'],
            ],
            [
                'attribute' => 'created_at',
                'format'    => ['date', 'php: d-m-Y']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?php

$js = <<< JS
 $(function () {
    $("#expensesList").DataTable({
    "responsive": true,
    "pageLength": 10,
    "paging": true,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": false,
    "bStateSave": true,
    "dom": "<'row'<'col-12 col-sm-6 d-flex align-content-md-start'f><'col-12 col-sm-6 d-flex justify-content-sm-end'l>>tp",
    "fnStateSave": function (oSettings, oData) {
        localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
    },
    "fnStateLoad": function () {
        var data = localStorage.getItem('DataTables_' + window.location.pathname);
        return JSON.parse(data);
    },
       "language": {
       "lengthMenu": 'Показать <select class="form-control form-control-sm">'+
      '<option value="10">10</option>'+
      '<option value="20">20</option>'+
      '<option value="50">50</option>'+
      '<option value="-1">Все</option>'+
      '</select>',
           "search": "Поиск:",
           "zeroRecords": "Совпадений не найдено",
    	  	"emptyTable": "В таблице отсутствуют данные",
           "paginate": {
                    "first": "Первая",
                    "previous": '<i class="fas fa-backward"></i>',
                    "last": "Последняя",
                    "next": '<i class="fas fa-forward"></i>'
                }
       }
    }).buttons().container().appendTo('#archive_wrapper .col-md-6:eq(0)');
  });
JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>