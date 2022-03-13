<?php


use common\models\EventSearch;
use common\modules\calendar\controllers\EventController;
use dosamigos\chartjs\ChartJs;
use yii\grid\GridView;

/* @var $dataProvider EventController */
/* @var $searchModel EventSearch */
/* @var $totalEvent EventController */
/* @var $totalSalary EventController */
/* @var $chartEventLabels EventController */
/* @var $chartEventData EventController */
/* @var $form common\modules\calendar\*/

?>


<div class="row">
    <div class="col-12 col-md-4">
        <?php echo $this->render('_search', ['model' => $searchModel,'form'=>$form,]); ?>

        <?= ChartJs::widget(
            [
                'type'          => 'bar',
                'id'            => 'structureEvent',
                'options'       => [
                    'legend' => [
                        'display' => true,
                        'title'   => [
                            'display' => true,
                            'text'    => ''
                        ],
                    ],

                ],
                'data'          => [

                    'labels' => $chartEventLabels,

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
                                'ticks' => [
                                    'beginAtZero' => true,
                                    'stacked'     => true,
                                    'fontColor'   => "#7f8c8d",
                                ]
                            ]
                        ],
                        'yAxes' => [
                            [
                                'ticks' => [
                                    'beginAtZero' => true,
                                    'stacked'     => true,
                                    'fontColor'   => "#7f8c8d",
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

        <?php echo GridView::widget(
            [
                'dataProvider'     => $dataProvider,
                'showFooter'       => true,
                'tableOptions'     => [
                    'class' => 'table table-striped table-bordered',
                    'id'    => 'master-events'
                ],
                'emptyText'        => 'Ничего не найдено',
                'emptyTextOptions' => [
                    'tag'   => 'div',
                    'class' => 'col-12 col-lg-6 mb-3 text-info master-events'
                ],
                'columns'          => [
                    //['class' => 'yii\grid\SerialColumn'],
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
                            return \common\models\Event::MissingMasterRates($model);
                        },
                    ],
                    [
                        'attribute'      => 'services.cost',
                        'footerOptions'  => ['class' => 'bg-info'],
                        'format'         => 'raw',
                        'contentOptions' => function ($model) {
                            $service_total = 0;
                            foreach ($model->services as $item) {
                                foreach ($model->master->rates as $master) {
                                    if ($master->service_id == $item->id) {
                                        $service_total += $item->cost;
                                    }
                                }
                            }
                            return ['data-total' => $service_total];
                        },
                        'value'          => function ($model) {
                            $service_one = null;
                            $service_total = 0;
                            foreach ($model->services as $item) {
                                foreach ($model->master->rates as $master) {
                                    if ($master->service_id == $item->id) {
                                        $service_one .= $item->cost . "<br>";
                                        $service_total += $item->cost;
                                    }
                                }
                            }
                            return $service_one . '<hr>' . Yii::$app->formatter->asCurrency(
                                    $service_total
                                );
                        },

                        'footer'    => Yii::$app->formatter->asCurrency($totalEvent),
                    ],
                    [
                        'attribute'      => 'salary',
                        'footerOptions'  => ['class' => 'bg-primary'],
                        'format'         => 'raw',
                        'contentOptions' => function ($model) {
                            $salary = 0;
                            foreach ($model->services as $service) {
                                foreach ($model->master->rates as $master) {
                                    if ($master->rate < 100 && $master->service_id == $service->id) {
                                        $salary += ($service->cost * $master->rate) / 100;
                                    }
                                }
                            }
                            return ['data-total' => $salary];
                        },
                        'value'          => function ($model) {
                            $salary = 0;
                            $salary_one = '';
                            $amount = '';
                            $amount_one = '';
                            foreach ($model->services as $service) {
                                foreach ($model->master->rates as $master) {
                                    if ($master->rate < 100 && $master->service_id == $service->id) {
                                        $salary_one .= ($service->cost * $master->rate) / 100 . '<br> ';
                                        $salary += ($service->cost * $master->rate) / 100;
                                    }
                                }
                            }

                            if ($salary > 0 && $salary_one > 0) {
                                $amount_one = $salary_one;
                                $amount = '<hr>' . Yii::$app->formatter->asCurrency($salary);
                            }
                            return $amount_one . $amount;
                        },
                        'footer'    => Yii::$app->formatter->asCurrency($totalSalary),
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

