<?php


use common\models\EventSearch;
use common\modules\calendar\controllers\EventController;
use dosamigos\chartjs\ChartJs;
use hail812\adminlte3\assets\PluginAsset;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $dataProvider EventController */
/* @var $searchModel EventSearch */
/* @var $totalEvent EventController */
/* @var $totalSalary EventController */
/* @var $chartEventLabels EventController */
/* @var $chartEventData EventController */

PluginAsset::register($this)->add(['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']);

/*echo'<pre>';
//var_dump(\common\models\ServiceUser::getUserServices());
var_dump(\common\models\Event::getUserEventService('2','1678'));
die();*/
?>


<div class="row">
    <div class="col-12 col-md-4">
        <?php
        echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= ChartJs::widget(
            [
                'type'          => 'bar',
                'id'            => 'structureEvent',
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

                    'labels'   => $chartEventLabels,
                    'datasets' => [
                        [
                            'data'             => $chartEventData,
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
                    'legend'    => [
                        'display'  => false,
                        'position' => 'bottom',
                        'labels'   => [
                            'fontSize'  => 14,
                            'fontColor' => "#7f8c8d",
                        ],
                    ],
                    'tooltips'  => [
                        'enabled'   => true,
                        'intersect' => true
                    ],
                    'hover'     => [
                        'mode' => 'single',
                    ],
                    'height'    => 100,
                    'widthUser' => 200,
                    'scales'    => [
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
                'dataProvider'     => $dataProvider,
                'showFooter'       => true,
                'tableOptions'     => [
                    'class' => 'table table-striped table-bordered',
                    'id'    => 'statistic_table'
                ],
                'emptyText'        => 'Ничего не найдено',
                'emptyTextOptions' => [
                    'tag'   => 'div',
                    'class' => 'col-12 col-lg-6 mb-3 text-info'
                ],


                'columns' => [


                    [
                        'attribute'     => 'master_id',
                        'format'        => 'raw',
                        'value'         => function ($model) {
                            return $model->master->username;
                        },
                        'footerOptions' => ['class' => 'bg-success'],
                        'footer'        => Yii::$app->formatter->asCurrency($totalEvent - $totalSalary),
                    ],
                    [
                        'attribute' => 'services.name',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            $service_name = '';
                            foreach ($model->services as $services) {
                                 $service_name .= $services->name . " </br>";
                            }

                            return $service_name;
                        },

                    ],
                    [
                        'attribute' => 'cost',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            $service_one = '';
                            $service_total = 0;
                            $service_none = '';
                            foreach ($model->services as $item) {

                                foreach ($model->master->rates as $master) {

                                    if ($master->service_id == $item->id) {
                                        $service_one .=  $item->cost . "</br>";
                                        $service_total += $item->cost;

                                    }
                                    if ( $master->service_id !== $item->id  ) {
                                        $service_one .=  \common\models\Event::getUserEventService(
                                            $model->master->id,
                                            $model->id
                                        );
                                    }
                                }
                                /*echo'<pre>';
                                var_dump( $master->rate);
                                die();*/
                            }

                            return $service_one .  '<hr>' . Yii::$app->formatter->asCurrency(
                                    $service_total
                                );
                        },
                        'footer'    => Yii::$app->formatter->asCurrency($totalEvent),
                    ],
                    [
                        'attribute' => 'salary',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            $salary = 0;
                            $salary_one = '';
                            $salary_none = '';
                            $amount = 0;
                            foreach ($model->services as $service) {
                                foreach ($model->master->rates as $master) {
                                    if ($master->rate < 100 && $master->service_id == $service->id) {
                                        $salary_none .= ($service->cost * $master->rate) / 100 . '<br> ';
                                        $salary += ($service->cost * $master->rate) / 100;

                                    }
                                    if ($master->rate < 100 && $service->id != $master->service_id  ) {
                                        $salary_none = \common\models\Event::getUserEventService(
                                            $model->master->id,
                                            $model->id
                                        );
                                       // echo'<pre>';
                                       // var_dump($service->cost);
                                    }

                                }

                            }
                            //die();
                            if ($salary > 0) {
                                $amount = '<hr>' . Yii::$app->formatter->asCurrency($salary);
                            }
                            return $salary_one . $salary_none . $amount;
                        },

                        'footer' => Yii::$app->formatter->asCurrency($totalSalary),

                    ],
                    [
                        'attribute' => 'client_id',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            return $model->client->username;
                        },
                    ],
                    [
                        'attribute' => 'event_time_start',
                        'format'    => ['date', 'php:d M Y'],
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
$("#statistic_table").DataTable({
"responsive": true,
"pageLength": 10,
"paging": true,
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
