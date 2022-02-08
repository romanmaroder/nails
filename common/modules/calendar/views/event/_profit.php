<?php


use common\models\EventSearch;
use common\modules\calendar\controllers\EventController;
use dosamigos\chartjs\ChartJs;
use hail812\adminlte3\assets\PluginAsset;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $profit EventController */
/* @var $searchModel EventSearch */
/* @var $dataProviderExpenseslist \common\models\ExpenseslistSearch */

PluginAsset::register($this)->add(['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']);

$event = \common\models\Event::find()
    ->select('DATE(event_time_start) as event_time_start, SUM(service.cost) as amountEvent')
    ->joinWith(['services'])
    ->groupBy(['DATE(event_time_start)'])
    ->orderBy(['DATE(event_time_start)'=>SORT_ASC])
    ->asArray()
    ->all();

$expenses = \common\models\Expenseslist::find()->select(
    'DATE(FROM_UNIXTIME(expenseslist.created_at)) as event_time_start , SUM(price) as amountExpenses'
)
    ->joinWith(['expenses'])
    ->groupBy(['DATE(FROM_UNIXTIME(created_at))'])
    ->orderBy(['DATE(FROM_UNIXTIME(created_at))'=>SORT_ASC])
    ->asArray()
    ->all();


$eventTotal = \common\models\Event::find()
    ->select('SUM(service.cost) as total')
    ->joinWith(['services'])
    ->asArray()
    ->one();

$expensesTotal = \common\models\Expenseslist::find()->select(
    ' SUM(price) as total'
)->asArray()->one();

$profit = $eventTotal['total'] - $expensesTotal['total'];

$merge        = array_merge($event, $expenses);

$dataProvider = new \yii\data\ArrayDataProvider(
    [
        'allModels' => $merge,
        'sort' => [
            'attributes' => [
                'event_time_start'=>[
                    'asc' => ['event_time_start' => SORT_ASC],
                    'desc' => ['event_time_start' => SORT_DESC],
                    'defaultOrder' => ['event_time_start' => SORT_ASC]
                ],

            ],
        ],
    ]
);


?>


<div class="row">
    <div class="col-12 col-md-4">
        <?php
        //echo '<pre>';
        //var_dump($expensesTotal['total']);

        // var_dump($dataProvider);
       /*  die();*/
        /* echo \Yii::$app->view->renderFile(
                   '@backend/views/expenseslist/_search.php',
                   ['model' => $searchModelExpenseslist]
               );
               */
        ?>

    </div>

    <div class="col-12 col-md-8">
        <?php
        Pjax::begin() ?>
        <?php
        echo GridView::widget(
            [
                'dataProvider'     => $dataProvider,
                'showFooter'       => true,
                'tableOptions'     => [
                    'class' => 'table table-striped table-bordered',
                    'id'    => 'expenseslist_table'
                ],
                'emptyText'        => 'Ничего не найдено',
                'emptyTextOptions' => [
                    'tag'   => 'div',
                    'class' => 'col-12 col-lg-6 mb-3 text-info'
                ],


                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    //'event_time_start',
                    [
                        'attribute' => 'event_time_start',
                        'format'    => ['date', 'php:d-m-Y'],
                        /*'value'     => function ($model) {
                            return $model->event_time_start;
                        },*/
                    ],


                    [
                        'attribute' => 'Прибыль',
                        'format'    => 'raw',
                        'value'=>'amountEvent',
                        'footer'=>$eventTotal['total']
                    ],
                    [
                        'attribute' => 'Затраты',
                        'format'    => 'raw',
                        'value'=>'amountExpenses',
                        'footer'=>$expensesTotal['total'],
                    ],
                    [
                        'attribute' => 'Итог',
                        'format'    => 'raw',
                        'footer'=>$profit,
                    ],



                ],
            ]
        );
        ?>
    </div>

</div>
<?php
Pjax::end() ?>
<?php
$js = <<< JS
$(function () {
$("#profit_table").DataTable({
"responsive": true,
"pageLength": 10,
"paging": false,
"searching": false,
"ordering": false,
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
}).buttons().container().appendTo('#statistic_table_wrapper .col-md-6:eq(0)');
});
JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>
