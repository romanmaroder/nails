<?php


use dosamigos\chartjs\ChartJs;
use hail812\adminlte3\assets\PluginAsset;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $dataProvider \common\modules\calendar\controllers\EventController */
/* @var $searchModel \common\models\EventSearch */

/*PluginAsset::register($this)->add(
    ['chart']
);*/

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
        \yii\widgets\Pjax::begin() ?>
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


        $name   = [];
        $amount = [];
        foreach ($dataProvider->models as $model) {
            foreach ($model->services as $key=>$item) {

                if (!in_array($item->name ,$name)){
                    $name[]   = $item->name;

                }
                if (!in_array($item->name,$amount)){
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
                'showFooter'   => true,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered',
                    'id'    => 'example2'
                ],
                'columns'      => [

                    [
                        'attribute' => 'client_id',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            return $model->client->username;
                        },
                    ],
                    [
                        'attribute' => 'master_id',
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            return $model->master->username;
                        },
                    ],
                    [
                        'attribute' => 'services.name',
                        'format'    => 'raw',
                        'value'     => function ($model) {
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
                        'format'    => 'raw',
                        'value'     => function ($model) {
                            $service_one   = '';
                            $service_total = 0;
                            foreach ($model->services as $item) {
                                $service_one   .= $item->cost . " </br>";
                                $service_total += $item->cost;
                            }
                            return $service_one . '<hr>' . $service_total;
                        },
                        'footer'    => \common\models\Event::getTotal($dataProvider),
                    ],
                    [
                        'attribute' => 'salary',
                        'format'    => 'raw',
                        'value'     => function ($model) {
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
                        'attribute' => 'event_time_start',
                        'format'    => ['date', 'php:Y-m-d'],
                    ],
                ],
            ]
        );


        ?>

    </div>
    <div class="row">
        <div class="col-12">

            <?= ChartJs::widget(
                [
                    'type'          => 'doughnut',
                    'id'            => 'structurePie',
                    'options'       => [
                        'height' => 200,
                        'width'  => 400,
                    ],
                    'data'          => [
                        'radius'   => "90%",
                        'labels'   => $name, // Your labels
                        'datasets' => [
                            [
                                'data'             => array_values($amount), // Your dataset
                                'label'            => $name,
                                'backgroundColor'  => [
                                    '#ADC3FF',
                                    '#FF9A9A',
                                    'rgba(190, 124, 145, 0.8)'
                                ],
                                'borderColor'      => [
                                    '#fff',
                                    '#fff',
                                    '#fff'
                                ],
                                'borderWidth'      => 1,
                                'hoverBorderColor' => ["#999", "#999", "#999"],
                            ]
                        ]
                    ],
                    'clientOptions' => [
                        'legend'              => [
                            'display'  => false,
                            'position' => 'bottom',
                            'labels'   => $name
                        ],
                        'tooltips'            => [
                            'enabled'   => true,
                            'intersect' => true
                        ],
                        'hover'               => [
                            'mode' => false
                        ],
                        'maintainAspectRatio' => false,

                    ],
                    'plugins'       =>
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
                            ctx.textBaseline = "middle";

                            var padding = 5;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize / 2) - padding);
                        });
                    }
                });
            }
        }]'
                        )
                ]
            )
            ?>

        </div>
    </div>
</div>
<?php
\yii\widgets\Pjax::end() ?>

