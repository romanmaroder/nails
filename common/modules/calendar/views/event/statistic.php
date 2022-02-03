<?php


use dosamigos\chartjs\ChartJs;
use hail812\adminlte3\assets\PluginAsset;
use yii\grid\GridView;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $dataProvider \common\modules\calendar\controllers\EventController */
/* @var $searchModel \common\models\EventSearch */

PluginAsset::register($this)->add(['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']);


/*echo '<pre>';
var_dump($dataProvider->models);
die();*/
?>


    <div class="row">
        <div class="col-12 col-md-4">
            <?php
            echo $this->render('_search', ['model' => $searchModel]); ?>

        </div>
        <div class="col-12 col-md-8">
            <?php
            Pjax::begin() ?>
            <?php
            /* echo ListView::widget(
                 [
                     'dataProvider'     => $dataProvider,
                     'options'          => [
                         'tag' => false,
                     ],
                     'layout'           => "{pager}\n{items}",
                     'itemOptions'      => ['tag' => null],
                     'itemView'         => function ($model, $key, $index) {
                         return \yii\helpers\Html::a(\yii\helpers\Html::encode($model->client->username),['/client/client/view','id'=>$model->client->id]). ' '
                             .$model->master->username . '</br>';
                     },
                     'emptyText'        => 'У вас нет сотрудников.',
                     'emptyTextOptions' => [
                         'tag'   => 'div',
                         'class' => 'col-12 col-lg-6 mb-3 text-info text-center'
                     ],
                 ]
             );*/

            /*$service1 = \common\models\Event::find()
                ->select(
                    '*,event_time_start,event_service.id, event_service.event_id, service.name, SUM(service.cost) as summa'
                )->joinWith(['services', 'eventService', 'master'])
                ->groupBy('service.name')
                ->asArray()
                ->all();*/


            $name = [];
            $amount = [];
            foreach ($dataProvider->models as $model) {
                foreach ($model->services as $key => $item) {
                    if (!in_array($item->name, $name)) {
                        $name[] = $item->name;
                    }
                    if (!in_array($item->name, $amount)) {
                        $amount[$item->name] += $item->cost;
                    }
                }
            }

            // echo '<pre>';
            //var_dump(count($name));
            // var_dump($amount);
            // die();

            /*$amount = [];
            $name   = [];
            foreach ($service1 as $item){
                $amount[] = $item['summa'];
                $name[]= $item['name'];


            }*/
            /* echo '<pre>';
             var_dump($model);
             die();*/
            echo GridView::widget(
                [
                    'dataProvider' => $dataProvider,
                    //'filterModel'  => $service1,
                    'showFooter' => true,
                    'tableOptions' => [
                        'class' => 'table table-striped table-bordered',
                        'id' => 'statistic_table'
                    ],
                    'emptyText' => 'Ничего не найдено',
                    'emptyTextOptions' => [
                        'tag' => 'div',
                        'class' => 'col-12 col-lg-6 mb-3 text-info'
                    ],


                    'columns' => [


                        [
                            'attribute' => 'master_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->master->username;
                            },
                        ],
                        [
                            'attribute' => 'services.name',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $service_name = '';
                                foreach ($model->services as $services) {
                                    $service_name .= $services->name . " </br>";
                                    // echo '<pre>';
                                    //var_dump($model);
                                    //var_dump($services->name);
                                }

                                return $service_name;
                            },
                        ],
                        [
                            'attribute' => 'cost',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $service_one = '';
                                $service_total = 0;
                                foreach ($model->services as $item) {
                                    $service_one .= $item->cost . " </br>";
                                    $service_total += $item->cost;
                                }
                                return $service_one . '<hr>' . Yii::$app->formatter->asCurrency($service_total);
                            },
                            'footer' => \common\models\Event::getTotal($dataProvider),
                        ],
                        [
                            'attribute' => 'salary',
                            'format' => 'raw',

                            'value' => function ($model) {
                                $salary = 0;

                                foreach ($model->services as $item) {
                                    if (!Yii::$app->authManager->getRolesByUser($model->master->id)['manager']) {
                                        $salary = $salary + $item->cost / 2 . "</br>";
                                    } else {
                                        $salary = '';
                                    }
                                }
                                return $salary;
                            },
                            'footer' => \common\models\Event::getSalary($dataProvider->models),

                        ],
                        [
                            'attribute' => 'client_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->client->username;
                            },
                        ],
                        [
                            'attribute' => 'event_time_start',
                            'format' => ['date', 'php:Y-m-d'],
                        ],

                    ],
                ]
            );


            ?>

        </div>
        <div class="row">
            <div class="col-12">

                <!--                --><? //= ChartJs::widget(
                //                    [
                //                        'type'          => 'doughnut',
                //                        'id'            => 'structurePie',
                //                        'options'       => [
                //                            'height' => 200,
                //                            'width'  => 400,
                //                        ],
                //                        'data'          => [
                //
                //                            'labels'   => $name, // Your labels
                //                            'datasets' => [
                //                                [
                //                                    'data'             => array_values($amount), // Your dataset
                //                                    'label'            => $name,
                //                                    'backgroundColor'  => [
                //                                        '#ADC3FF',
                //                                        '#FF9A9A',
                //                                        'rgba(190, 124, 145, 0.8)',
                //                                        'rgba(190, 124, 145, 0.8)',
                //                                        'rgba(190, 124, 145, 0.8)',
                //                                    ],
                //                                    'borderColor'      => [
                //                                        '#fff'
                //                                    ],
                //                                    'borderWidth'      => 1,
                //                                    'hoverBorderColor' => ["#999"],
                //                                ]
                //                            ]
                //                        ],
                //                        'clientOptions' => [
                //                            'legend'              => [
                //                                'display'  => false,
                //                                'position' => 'bottom',
                //                                'labels'   => [
                //                                    'fontSize'  => 14,
                //                                    'fontColor' => "#9da3ab",
                //                                ],
                //                            ],
                //                            'tooltips'            => [
                //                                'enabled'   => true,
                //                                'intersect' => true
                //                            ],
                //                            'hover'               => [
                //                                'mode' => 'single',
                //                            ],
                //                            'maintainAspectRatio' => false,
                //                            'animation'           => [
                //                                'duration'   => 500,
                //                                'easing'     => "easeOutQuart",
                //                                /*'onComplete' =>
                //                                    new \yii\web\JsExpression(
                //                                        '
                //                                        function () {
                //      var ctx = this.chart.ctx;
                //      ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, "normal", Chart.defaults.global.defaultFontFamily);
                //      ctx.textAlign = "center";
                //      ctx.textBaseline = "bottom";
                //
                //      this.data.datasets.forEach(function (dataset) {
                //
                //        for (var i = 0; i < dataset.data.length; i++) {
                //          var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                //              total = dataset._meta[Object.keys(dataset._meta)[0]].total,
                //              mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
                //              start_angle = model.startAngle,
                //              end_angle = model.endAngle,
                //              mid_angle = start_angle + (end_angle - start_angle)/2;
                //
                //          var x = mid_radius * Math.cos(mid_angle);
                //          var y = mid_radius * Math.sin(mid_angle);
                //
                //          ctx.fillStyle = "#000";
                //          if (i == 3){ // Darker text color for lighter background
                //            ctx.fillStyle = "#444";
                //          }
                //
                //
                //          var percent = String(Math.round(dataset.data[i]/total*100)) + "%";
                //          ctx.fillText(dataset.data[i], model.x + x, model.y + y);
                //          // Display percent in another line, line break doesn\'t work for fillText
                //          ctx.fillText(percent, model.x + x, model.y + y + 15);
                //        }
                //      });
                //    }')*/
                //                            ],
                //
                //                        ],
                //                        /*'plugins'       =>
                //                            new \yii\web\JsExpression(
                //                                '
                //        [{
                //            afterDatasetsDraw: function(chart, easing) {
                //                var ctx = chart.ctx;
                //                chart.data.datasets.forEach(function (dataset, i) {
                //                    var meta = chart.getDatasetMeta(i);
                //
                //                    if (!meta.hidden) {
                //                        meta.data.forEach(function(element, index) {
                //                            // Draw the text in black, with the specified font
                //                            ctx.fillStyle = "rgb(0, 0, 0)";
                //
                //                            var fontSize = 16;
                //                            var fontStyle = "normal";
                //                            var fontFamily = "Helvetica";
                //                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);
                //
                //                            // Just naively convert to string for now
                //                            //var dataString = dataset.data[index].toString()+"%";
                //                            var dataString = dataset.data[index].toString();
                //
                //                            // Make sure alignment settings are correct
                //                            ctx.textAlign = "center";
                //                            ctx.textBaseline = "center";
                //
                //                            var padding = 5;
                //                            var position = element.tooltipPosition();
                //
                //
                //                            ctx.fillText(dataString, position.x, position.y );
                //
                //                        });
                //                    }
                //                });
                //            }
                //        }]'
                //                            )*/
                //                    ]
                //                )
                //                ?>

                <?= ChartJs::widget(
                    [
                        'type' => 'bar',
                        'id' => 'structurePie',
                        'options' => [

                            'legend' => [
                                'display' => false,
                                'title' => [
                                    'display' => true,
                                    'text' => ''
                                ]
                            ],

                        ],
                        'data' => [

                            'labels' => $name, // Your labels
                            'datasets' => [
                                [
                                    'data' => array_values($amount), // Your dataset
                                    'backgroundColor' => [
                                        '#ADC3FF',
                                        '#FF9A9A',
                                        'rgba(190, 124, 145, 0.8)',
                                        'rgba(190, 124, 145, 0.8)',
                                        'rgba(190, 124, 145, 0.8)',
                                    ],
                                    'borderColor' => [
                                        '#fff'
                                    ],
                                    'borderWidth' => 1,
                                    'hoverBorderColor' => ["#999"],

                                ]
                            ]
                        ],
                        'clientOptions' => [
                            'legend' => [
                                'display' => false,
                                'position' => 'bottom',
                                'labels' => [
                                    'fontSize' => 14,
                                    'fontColor' => "#7f8c8d",
                                ],
                            ],
                            'tooltips' => [
                                'enabled' => true,
                                'intersect' => true
                            ],
                            'hover' => [
                                'mode' => 'single',
                            ],
                            'height' => 100,
                            'width' => 200,
                            'scales' => [
                                'xAxes' => [
                                    [
                                        'stacked' => true,
                                    ]
                                ],
                                'yAxes' => [
                                    [
                                        'ticks' => [
                                            'beginAtZero' => true,
                                            'stacked' => true,
                                        ]
                                    ]
                                ]
                            ]
                        ]
//                            'maintainAspectRatio' => false,
//                            'animation'           => [
//                                'duration'   => 500,
//                                'easing'     => "easeOutQuart",
//                                /*'onComplete' =>
//                                    new \yii\web\JsExpression(
//                                        '
//                                        function () {
//      var ctx = this.chart.ctx;
//      ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontFamily, "normal", Chart.defaults.global.defaultFontFamily);
//      ctx.textAlign = "center";
//      ctx.textBaseline = "bottom";
//
//      this.data.datasets.forEach(function (dataset) {
//
//        for (var i = 0; i < dataset.data.length; i++) {
//          var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
//              total = dataset._meta[Object.keys(dataset._meta)[0]].total,
//              mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
//              start_angle = model.startAngle,
//              end_angle = model.endAngle,
//              mid_angle = start_angle + (end_angle - start_angle)/2;
//
//          var x = mid_radius * Math.cos(mid_angle);
//          var y = mid_radius * Math.sin(mid_angle);
//
//          ctx.fillStyle = "#000";
//          if (i == 3){ // Darker text color for lighter background
//            ctx.fillStyle = "#444";
//          }
//
//
//          var percent = String(Math.round(dataset.data[i]/total*100)) + "%";
//          ctx.fillText(dataset.data[i], model.x + x, model.y + y);
//          // Display percent in another line, line break doesn\'t work for fillText
//          ctx.fillText(percent, model.x + x, model.y + y + 15);
//        }
//      });
//    }')*/
//                            ],
//
//                        ],
                        /*'plugins'       =>
                            new \yii\web\JsExpression(
                                '
        [{
            afterDatasetsDraw: function(chart, easing) {
                var ctx = chart.ctx;
                chart.data.datasets.forEach(function (dataset, i) {
                    var meta = chart.getDatasetMeta(i);

                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = "rgb(0, 0, 0)";

                            var fontSize = 16;
                            var fontStyle = "normal";
                            var fontFamily = "Helvetica";
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Just naively convert to string for now
                            //var dataString = dataset.data[index].toString()+"%";
                            var dataString = dataset.data[index].toString();

                            // Make sure alignment settings are correct
                            ctx.textAlign = "center";
                            ctx.textBaseline = "center";

                            var padding = 5;
                            var position = element.tooltipPosition();


                            ctx.fillText(dataString, position.x, position.y );

                        });
                    }
                });
            }
        }]'
                            )*/
                    ]
                )
                ?>

            </div>
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