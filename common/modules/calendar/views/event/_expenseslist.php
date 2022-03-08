<?php


use common\components\totalCell\NumberColumn;
use common\models\EventSearch;
use common\modules\calendar\controllers\EventController;
use dosamigos\chartjs\ChartJs;
use hail812\adminlte3\assets\PluginAsset;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $dataProviderExpenseslist EventController */
/* @var $searchModelExpenseslist EventSearch */
/* @var $chartExpensesLabels EventController */
/* @var $chartExpensesData EventController */

PluginAsset::register($this)->add(['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']);

?>


<div class="row">
    <div class="col-12 col-md-4">
        <?php  echo \Yii::$app->view->renderFile(
            '@backend/views/expenseslist/_search.php',
            ['model' => $searchModelExpenseslist]
        );
        ?>

        <?= ChartJs::widget(
            [
                'type'          => 'bar',
                'id'            => 'structureExpenses',
                'options'       => [

                    'legend' => [
                        'display' => false,
                        'title'   => [
                            'display' => true,
                            'text'    => ''
                        ]
                    ],

                ],
                'data'          => [

                    'labels'   => $chartExpensesLabels,
                    'datasets' => [
                        [
                            'data'             => $chartExpensesData,
                            'backgroundColor'  => [
                                '#ADC3FF',
                                '#FF9A9A',
                                '#9b59b6',
                                '#f1c40f',
                                '#e67e22',
                                '#16a085',
                                '#b8e994',
                                '#1e3799',
                                '#fa983a',
                                '#eb2f06',
                                '#38ada9',
                                '#b71540',
                                '#40407a',
                                '#ccae62',
                                '#ff6b81',
                                '#c23616',
                                '#44bd32',
                                '#e1b12c',
                                '#c23616',
                                '#e84118',
                                '#10ac84',
                                '#48dbfb',
                                '#f368e0',
                            ],
                            'borderColor'      => [
                                '#fff'
                            ],
                            'borderWidth'      => 1,
                            'hoverBorderColor' => ["#999"],

                        ]
                    ]
                ],
                'clientOptions' => [
                    'legend'   => [
                        'display'  => false,
                        'position' => 'bottom',
                        'labels'   => [
                            'fontSize'  => 14,
                            'fontColor' => "#7f8c8d",
                        ],
                    ],
                    'tooltips' => [
                        'enabled'   => true,
                        'intersect' => true
                    ],
                    'hover'    => [
                        'mode' => 'single',
                    ],
                    'height'   => 100,
                    'width'    => 200,
                    'scales'   => [
                        'xAxes' => [
                            [
                                'stacked' => true,
                            ]
                        ],
                        'yAxes' => [
                            [
                                'ticks' => [
                                    'beginAtZero' => true,
                                    'stacked'     => true,
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        )

        ?>
    </div>

    <div class="col-12 col-md-8">
        <?php
        Pjax::begin() ?>
        <?php
        echo GridView::widget(
            [
                'dataProvider' => $dataProviderExpenseslist,
                'showFooter' => true,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered',
                    'id' => 'expenseslist_table'
                ],
                'emptyText' => 'Ничего не найдено',
                'emptyTextOptions' => [
                    'tag' => 'div',
                    'class' => 'col-12 col-lg-6 mb-3 text-info'
                ],


                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'expenses_id',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            return $model->expenses->title;
                        },
                    ],
                    [
                        'class'         => NumberColumn::class,
                        'attribute' => 'price',
                        'format'    => 'raw',
                        'footerOptions' => ['class' => 'bg-success'],
                        'value'     => function ($model) {
                            return $model->price;
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:d M Y'],
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
$("#expenseslist_table").DataTable({
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
