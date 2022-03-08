<?php

use common\components\totalCell\NumberColumn;
use common\modules\calendar\controllers\EventController;
use kartik\daterange\DateRangePicker;
use yii\bootstrap4\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $dataHistory EventController */

/*echo '<pre>';
var_dump($dataHistory->models);
die();*/
?>
<?php
if (Yii::$app->session->hasFlash('info')): ?>
    <div class="alert alert-info alert-dismissible mt-3" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        <?php
        echo Yii::$app->session->getFlash('info'); ?>
    </div>
<?php
endif; ?>
    <div class="row">
        <div class="col-12 col-md-3">

            <?php
            ActiveForm::begin(
                [
                    'id'     => 'search',
                    'method' => 'GET'
                ]
            ); ?>

            <?php

            echo DateRangePicker::widget(
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
                        'id'    => 'btn-search',
                    ]
                ) ?>
            </div>

            <?php
            ActiveForm::end(); ?>

            <?php
            $form = ActiveForm::begin(
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
                        'id'    => 'btn-save',
                        'name'  => 'archive',
                        'value' => 'archive',
                    ]
                ) ?>
            </div>
            <?php
            ActiveForm::end(); ?>


        </div>
        <div class="col-12 col-md-9">

            <?php
            echo GridView::widget(
                [
                    'dataProvider'     => $dataHistory,
                    'showFooter'       => true,
                    'summary'          => '',
                    'tableOptions'     => [
                        'class' => 'table table-striped table-bordered text-center',
                        'id'    => 'historyList'
                    ],
                    'emptyText'        => 'Ничего не найдено',
                    'emptyTextOptions' => [
                        'tag'   => 'div',
                        'class' => 'col-12 col-lg-6 mb-3 text-info'
                    ],
                    'columns'          => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute'     => 'service.name',
                            'contentOptions'=>[
                                'class' => 'text-left'
                            ],
                        ],
                        [
                            'attribute'     => 'event.master.username',
                            'contentOptions'=>[
                                'class' => 'text-left'
                            ],
                        ],
                        [
                            'class'         => NumberColumn::class,
                            'attribute'     => 'amount',
                            'footerOptions' => ['class' => 'bg-success'],
                        ],
                        [
                            'attribute' => 'event.salary',
                            'value'     => function ($model) {
                                $salary = null;
                                foreach ($model['event']['master']['rates'] as $rate) {
                                    $salary = $model['amount'] * $rate['rate'] / 100;
                                }
                                return $salary;
                            }
                        ],
                        [
                            'attribute' => 'event.event_time_start',
                            'format'    => ['date', 'php:Y-M'],
                        ]
                    ],
                ]
            );
            ?>

        </div>
    </div>


<?php

$js = <<< JS
 $(function () {
    $("#historyList").DataTable({
    "responsive": true,
    "pageLength": 10,
    "paging": true,
    "searching": true,
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
    }).buttons().container().appendTo('#historyList_wrapper .col-md-6:eq(0)');
  });
JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>