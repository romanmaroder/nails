<?php

use common\components\totalCell\NumberColumn;
use common\modules\calendar\controllers\EventController;
use hail812\adminlte3\assets\PluginAsset;
use kartik\daterange\DateRangePicker;
use yii\bootstrap4\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $dataHistory EventController */


?>
<?php
Pjax::begin() ?>
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
        <?php
        endif; ?>
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

            <?php
            echo GridView::widget(
                [
                    'dataProvider'     => $dataHistory,
                    'showFooter'       => true,
                    'tableOptions'     => [
                        'class' => 'table table-striped table-bordered text-center display',
                        'id'    => 'historyTable'
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
                            'footerOptions' => ['class' => 'bg-success'],
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
Pjax::end() ?>
<?php

$js = <<< JS
 $(function () {
    /*$("#historyTable").DataTable({
    "responsive": true,
    "pageLength": 10,
    "paging": true,
    "searching": true,
    "ordering": false,
    "info": false,
    "autoWidth": false,
    "bStateSave": true,
    "dom": "<'row'<'col-12 col-sm-6 d-flex align-content-md-start'f><'col-12 col-sm-6 d-flex justify-content-sm-end'l>>tp",
    "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api();
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 2 )
                .nodes()
                .reduce( function (a, b) {
                    return intVal(a) + intVal($(b).attr('data-total'));
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 2, { page: 'current'} )
                .nodes()
                .reduce( function (a, b) {
                    return intVal(a) + intVal($(b).attr('data-total'));
                }, 0 );
            
            // Update footer
            $( api.column( 2 ).footer() ).html(
                pageTotal.toLocaleString('ru') + ' &#8381;'+' <hr> '+ total.toLocaleString('ru') + ' &#8381;'
            );
            
        },
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
    }).buttons().container().appendTo('#historyTable_wrapper .col-md-6:eq(0)');
    $(document).on('pjax:complete', function() {
    $('#historyTable').DataTable();
});*/
  });
JS;

//$this->registerJs($js, $position = yii\web\View::POS_READY, $key = 'history');

?>