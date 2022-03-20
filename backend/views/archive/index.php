<?php

use backend\controllers\ArchiveController;
use common\components\totalCell\NumberColumn;
use common\models\Service;
use common\models\User;
use hail812\adminlte3\assets\PluginAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArchiveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

PluginAsset::register($this)->add(['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']);

$this->title                   = 'Архив';
$this->params['breadcrumbs'][] = $this->title;


?>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="archive-index">
                    <?php Pjax::begin(); ?>

                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'rowOptions'   => function ($model) {
                                return ['style' => 'background-color:' . $model->user->profile->color];
                            },
                            //'filterModel'  => $searchModel,
                            'summary'      => false,
                            'showFooter'   => true,
                            'tableOptions' => [
                                'class' => 'table table-striped table-bordered text-center',
                                'id'    => 'archive'
                            ],
                            'columns'      => [
                                // ['class' => 'yii\grid\SerialColumn'],

                                //'id',
                                [
                                    'attribute'      => 'user_id',
                                    'filter'         => Select2::widget(
                                        [
                                            'model'         => $searchModel,
                                            'attribute'     => 'user_id',
                                            'data'          => User::getMasterList(),
                                            'value'         => $searchModel->user_id,
                                            'language'      => 'ru',
                                            'theme'         => Select2::THEME_KRAJEE_BS4,
                                            'size'          => Select2::SMALL,
                                            'options'       => [
                                                'placeholder'  => 'Мастер',
                                                'multiple'     => false,
                                                'autocomplete' => 'off',
                                            ],
                                            'pluginOptions' => [
                                                'tags'       => true,
                                                'allowClear' => true,
                                            ],
                                        ]
                                    ),
                                    'contentOptions' => [
                                        'class' => 'text-left'
                                    ],
                                    'value'          => function ($model) {
                                        return $model['user']['username'];
                                    }
                                ],
                                [
                                    'attribute'      => 'service_id',
                                    'filter'         => Select2::widget(
                                        [
                                            'model'         => $searchModel,
                                            'attribute'     => 'service_id',
                                            'language'      => 'ru',
                                            'data'          => Service::getServiceList(),
                                            'theme'         => Select2::THEME_KRAJEE_BS4,
                                            'size'          => Select2::SMALL,
                                            'options'       => [
                                                'placeholder'  => 'Выберите услугу ...',
                                                'multiple'     => true,
                                                'autocomplete' => 'off',
                                            ],
                                            'pluginOptions' => [
                                                'tags'       => true,
                                                'allowClear' => true,
                                            ],
                                        ]
                                    ),
                                    'contentOptions' => [
                                        'class' => 'text-left'
                                    ],
                                    'value'          => function ($model) {
                                        return $model['service']['name'];
                                    }
                                ],
                                [
                                    'class'          => NumberColumn::class,
                                    'attribute'      => 'amount',
                                    'contentOptions' => function ($model) {
                                        return ['data-total' => $model->amount];
                                    },
                                    'footerOptions'  => ['class' => 'bg-info'],
                                ],
                                [
                                    'class'          => NumberColumn::class,
                                    'attribute'      => 'salary',
                                    'contentOptions' => function ($model) {
                                        if ($model->amount == $model->salary) {
                                            $model->salary = 0;
                                        }
                                        return ['data-total' => $model->salary];
                                    },
                                    'value'          => function ($model) {
                                        if ($model->amount == $model->salary) {
                                            return 0;
                                        }
                                        return $model->salary;
                                    },
                                    'footerOptions'  => ['class' => 'bg-secondary'],
                                ],
                                [
                                    'class'         => NumberColumn::class,
                                    'attribute'     => 'profit',
                                    'footerOptions' => ['class' => 'bg-success'],
                                    'value'         => function ($model) {
                                        if ($model->amount == $model->salary) {
                                            return $model->amount;
                                        }
                                        return $model->amount - $model->salary;
                                    },

                                ],
                                [
                                    'attribute' => 'date',
                                    'filter'    => DatePicker::widget(
                                        [
                                            'model'         => $searchModel,
                                            'attribute'     => 'date',
                                            'options'       => [
                                                'placeholder'  => 'Выберите дату ...',
                                                'autocomplete' => 'off',
                                            ],
                                            'size'          => 'sm',
                                            'pluginOptions' => [
                                                'todayHighlight' => true,
                                                'weekStart'      => 1, //неделя начинается с понедельника
                                                'autoclose'      => true,
                                                'orientation'    => 'bottom auto',
                                                'clearBtn'       => true,
                                                'todayBtn'       => 'linked',
                                                'format'         => 'mm-yyyy'
                                            ]
                                        ]
                                    ),
                                ],
                                [
                                    'class'    => 'yii\grid\ActionColumn',
                                    'template' => '{view}',

                                ],
                            ],
                        ]
                    ); ?>

                    <?php Pjax::end(); ?>

                </div>
            </div>
        </div>
    </div>

<?php
$js = <<< JS
$(function () {
    
    function initTable (){
        $("#archive").DataTable({
"responsive": true,
"pageLength": 10,
"paging": true,
"searching": true,
"ordering": false,
"info": true,
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
                            totalAmount = api
                                .column( 2)
                                .nodes()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal($(b).attr('data-total'));
                                }, 0 );
                            // Total over this page
                            pageTotalAmount = api
                                .column( 2, { page: 'current'} )
                                .nodes()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal($(b).attr('data-total'));
                                }, 0 );
                            // Update footer
                            $( api.column( 2 ).footer() )
                            //.html( pageTotalAmount.toLocaleString('ru') + ' &#8381;'+' <hr> '+ totalAmount .toLocaleString('ru') + ' &#8381;');
                            .html( pageTotalAmount.toLocaleString('ru') + ' &#8381;');
                            // Total over all pages
                            totalSalary = api
                                .column( 3 )
                                .nodes()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal($(b).attr('data-total'));
                                }, 0 );
                            // Total over this page
                            pageTotalSalary = api
                                .column( 3, { page: 'current'} )
                                .nodes()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal($(b).attr('data-total'));
                                }, 0 );
                            // Update footer
                            if ( pageTotalSalary == 0 ){
                                 $( api.column( 3 ).footer() )
                                 //.html(pageTotalSalary.toLocaleString('ru') + ' &#8381;'+' <hr> '+ totalSalary.toLocaleString('ru') + ' &#8381;');
                                 .html('-').css({'text-align':'center'});
                            }else{
                                 $( api.column( 3 ).footer() ).html(pageTotalSalary.toLocaleString('ru') + ' &#8381;').css({'text-align':'center'});
                            }
                            
                            //
                            
                             var diffPage =  pageTotalAmount - pageTotalSalary;
                             var diffTotal =  totalAmount - totalSalary;
                            $( api.column( 4 ).footer() )
                            //.html( diffPage.toLocaleString('ru') + ' &#8381;'+' <hr>'+ diffTotal.toLocaleString('ru') + ' &#8381;');
                            .html( diffPage.toLocaleString('ru') + ' &#8381;');
                            
                            
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
}).buttons().container().appendTo('#archive_wrapper .col-md-6:eq(0)');
    }
    
     $(document).on('pjax:complete', function() {
         initTable();
     });
  initTable();
});
JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>