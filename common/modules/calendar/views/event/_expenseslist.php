<?php


use common\components\totalCell\NumberColumn;
use common\models\EventSearch;
use common\modules\calendar\controllers\EventController;
use dosamigos\chartjs\ChartJs;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $dataProviderExpenseslist EventController */
/* @var $searchModelExpenseslist EventSearch */
/* @var $chartExpensesLabels EventController */
/* @var $chartExpensesData EventController */

?>
<?php Pjax::begin(
    [
        'id' => 'pjax-gridview',
    ]
) ?>

<div class="row">
    <div class="col-12 col-md-4">
        <?php echo \Yii::$app->view->renderFile(
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
        echo GridView::widget(
            [
                'dataProvider'     => $dataProviderExpenseslist,
                'showFooter'       => true,
                'tableOptions'     => [
                    'class' => 'table table-striped table-bordered text-center expenseslist',
                    'id'    => 'expenseslist'
                ],
                'emptyText'        => 'Ничего не найдено',
                'emptyTextOptions' => [
                    'tag'   => 'div',
                    'class' => 'col-12 col-lg-6 mb-3 text-info'
                ],


                'columns' => [
                    [
                        'class'         => 'yii\grid\SerialColumn',
                        'footerOptions' => ['class' => 'bg-success'],
                    ],
                    [
                        'visible' => false
                    ],
                    [
                        'visible' => false
                    ],
                    [
                        'attribute'      => 'expenses_id',
                        'format'         => 'raw',
                        'contentOptions' => [
                            'class' => 'text-left'
                        ],
                        'value'          => function ($model) {
                            return $model->expenses->title;
                        },
                    ],
                    [
                        'class'          => NumberColumn::class,
                        'attribute'      => 'price',
                        'contentOptions' => function ($model) {
                            return ['data-total' => $model['price']];
                        },

                        'format'        => 'raw',
                        'footerOptions' => ['class' => 'bg-info'],
                        'value'         => function ($model) {
                            return $model->price;
                        },
                    ],

                    [
                        'attribute' => 'created_at',
                        'format'    => ['date', 'php:d M Y'],
                    ],


                ],
            ]
        );
        ?>

    </div>

</div>
<?php Pjax::end() ?>

