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
    <div class="archive-index">

        <!--<h1><?
        /*= Html::encode($this->title) */ ?></h1>

    <p>
        <?
        /*= Html::a('Create Archive', ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>-->

        <?php
        Pjax::begin(); ?>
        <?php
        /*echo $this->render('_search', ['model' => $searchModel]); */ ?>

        <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'rowOptions'   => function ($model) {
                    return ['style' => 'background-color:' . $model->user->profile->color];
                },
                'filterModel'  => $searchModel,
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
                        'class'         => NumberColumn::class,
                        'attribute'     => 'amount',
                        'footerOptions' => ['class' => 'bg-info'],
                    ],
                    [
                        'class'         => NumberColumn::class,
                        'attribute'     => 'salary',
                        'value'         => function ($model) {
                            if ($model->amount == $model->salary) {
                                return 0;
                            }
                            return $model->salary;
                        },
                        'footerOptions' => ['class' => 'bg-secondary'],
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




        <?php
        Pjax::end(); ?>

    </div>
<?php
$js = <<< JS
$(function () {
$("#archive").DataTable({
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
}).buttons().container().appendTo('#archive_wrapper .col-md-6:eq(0)');

 $(document).on('pjax:complete', function() {
      $("#archive").DataTable({
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
}).buttons().container().appendTo('#archive_wrapper .col-md-6:eq(0)');
    });

});
JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>