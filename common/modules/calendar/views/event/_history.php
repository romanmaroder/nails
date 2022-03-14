<?php

use common\components\totalCell\NumberColumn;
use common\modules\calendar\controllers\EventController;
use kartik\daterange\DateRangePicker;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $dataHistory EventController */

/*echo'<pre>';
var_dump($dataHistory->models);
die();*/
?>

<div class="row">
    <?php if (Yii::$app->session->hasFlash('info')): ?>
        <div class="col-12">
            <div class="alert alert-info alert-dismissible mt-3" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <?php
                echo Yii::$app->session->getFlash('info'); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-12 col-md-3">
        <?php echo DateRangePicker::widget(
            [
                'name'           => 'archive',
                'value'          => Yii::$app->request->queryParams['archive'],
                'useWithAddon'   => false,
                'convertFormat'  => true,
                'startAttribute' => 'from_date',
                'endAttribute'   => 'to_date',
                'initRangeExpr'  => false,
                'pluginOptions'  => [
                    'locale' => [
                        'format' => 'Y-m-d',
                        ''       => true,
                    ],
                ],
                'hideInput'      => true,

            ]
        );; ?>

        <div class="form-group my-3">
            <?= Html::submitButton(
                'Поиск',
                [
                    'class' => 'btn btn-sm btn-primary',
                    'name'  => 'history',
                    'value' => 'search',
                ]
            ) ?>
            <?= Html::submitButton(
                'Сохранить',
                [
                    'class' => 'btn btn-sm btn-primary',
                    'name'  => 'history',
                    'value' => 'save',
                ]
            ) ?>
        </div>

    </div>

    <div class="col-12 col-md-9">
        <?php
        echo GridView::widget(
            [
                'dataProvider'     => $dataHistory,
                'showFooter'       => true,
                'tableOptions'     => [
                    'class' => 'table table-striped table-bordered text-center history',
                    'id'    => 'history'
                ],
                'emptyText'        => 'Ничего не найдено',
                'emptyTextOptions' => [
                    'tag'   => 'div',
                    'class' => 'col-12 col-lg-6 mb-3 text-info'
                ],
                'columns'          => [
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute'      => 'service.name',
                        'footerOptions'  => ['class' => 'bg-success'],
                        'contentOptions' => [
                            'class' => 'text-left'
                        ],
                    ],
                    [
                        'attribute'      => 'event.master.username',
                        'contentOptions' => [
                            'class' => 'text-left'
                        ],
                    ],
                    [
                        'class'          => NumberColumn::class,
                        'attribute'      => 'amount',
                        'contentOptions' => function ($model) {
                            return ['data-total' => $model['amount']];
                        },
                        'footerOptions'  => ['class' => 'bg-info'],
                    ],
                    [
                        'attribute' => 'event.salary',
                        'value'     => function ($model) {
                            $salary = null;
                            foreach ($model['event']['master']['rates'] as $rate) {
                                $salary = $model['amount'] * $rate['rate'] / 100;
                            }
                            return $salary;
                        },
                        /*'contentOptions' => function ($model) {
                            $salary = null;
                            foreach ($model['event']['master']['rates'] as $rate) {
                                $salary = $model['amount'] * $rate['rate'] / 100;
                            }
                            return ['data-total' => $salary ];
                        },*/
                    ],
                    [
                        'attribute' => 'event.event_time_start',
                        'format'    => ['date', 'php:Y-M'],
                    ],

                ],
            ]
        ); ?>
    </div>

</div>


