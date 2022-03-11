<?php

use common\components\totalCell\NumberColumn;
use common\modules\calendar\controllers\EventController;
use kartik\daterange\DateRangePicker;
use yii\bootstrap4\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $dataHistory EventController */


?>
<?php
Pjax::begin([
                'id'=>'history',
                 'timeout' => 5000
            ]) ?>
    <div class="row">
        <?php if (Yii::$app->session->hasFlash('info')): ?>
        <div class="col-12">
            <div class="alert alert-info alert-dismissible mt-3" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('info'); ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="col-12 col-md-3">

            <?php
            ActiveForm::begin(
                [
                    'id'     => 'search',
                    'method' => 'GET'
                ]
            ); ?>

            <?php echo DateRangePicker::widget(
                [
                    'name'           => 'archive',
                    'value'          => '',
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
                        //'id'    => 'btn-search',
                    ]
                ) ?>
            </div>

            <?php
            ActiveForm::end(); ?>

            <?php $form = ActiveForm::begin(
                [
                    'id'     => 'history',
                    'method' => 'post',
                ]
            ); ?>
            <div class="form-group ">
                <?= Html::submitButton(
                    'Отправить',
                    [
                        'class' => 'btn btn-sm btn-primary',
                        // 'id'    => 'btn-save',
                        'name'  => 'save-archive',
                        'value' => 'archive',
                    ]
                ) ?>
            </div>
            <?php
            ActiveForm::end(); ?>


        </div>
        <div class="col-12 col-md-9">

            <?php echo GridView::widget(
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
                            'footerOptions' => ['class' => 'bg-success'],
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
                            'class'         => NumberColumn::class,
                            'attribute'     => 'amount',
                            'contentOptions' => function ($model) {
                                return ['data-total' => $model['amount']];
                            },
                            'footerOptions' => ['class' => 'bg-info'],
                        ],
                        [
                            'attribute'      => 'event.salary',
                            'value'          => function ($model) {
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

<?php
Pjax::end() ?>
