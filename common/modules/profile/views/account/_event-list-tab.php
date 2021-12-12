<?php


use yii\grid\GridView;
use yii\helpers\Html;

/* @var $dataProvider */


    echo GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'summary' => "",
            #'filterModel'  => null,

            'tableOptions' => [
                'class' => 'table table-bordered table-hover',
                'style' => 'width:100%',
                'id' => 'eventsList',
            ],
            'options' => [
                #'class' => 'table-responsive',
            ],
            'emptyText' => 'У Вас нет записей',
            'emptyTextOptions' => [
                'tag' => 'div',
                'class' => 'col-12 col-lg-6 mb-3 text-info'
            ],
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'client_id',
                    'format' => 'raw',
                    'visible' => Yii::$app->user->can('perm_view-calendar'),
                    'value' => function ($client) {
                        return Html::a(
                            $client['client']['username'],
                            ['/client/client/view', 'id' => $client['client']['id']]

                        );
                    }
                ],

                [
                    'attribute' => 'event_time_start',
                    'contentOptions' => ['style' => 'white-space: nowrap;'],
                    'label' => 'Дата',
                    'format' => ['date', 'php:d-m-Y'],
                ],
                [
                    'attribute' => 'event_time_start',
                    'label' => 'Время',
                    'format' => ['date', 'php:H:i'],
                ],
                [
                    'attribute' => 'master_id',
                    'format' => 'raw',
                    'visible' => Yii::$app->user->can('user'),
                    'value' => function ($master) {
                        return $master['master']['username'];
                    }
                ],
                [
                    'attribute' => 'description',
                    'contentOptions' => ['style' => 'white-space: nowrap;'],
                ],
                //'notice',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => "{view}\n\n\n{sms}",
                    'visibleButtons' => [
                        'sms' => function ($model) {
                            return Yii::$app->user->can('manager');
                        }
                    ],
                    'buttons' => [
                        'sms' => function ($url, $model, $key) {
                            return $model['client']['phone'] ?
                                Html::a(
                                    '<i class="far fa-envelope"></i>',
                                    'sms:' . $model['client']['phone'] . Yii::$app->smsSender->checkOperatingSystem(
                                    ) . Yii::$app->smsSender->messageText(
                                        $model['event_time_start']
                                    )
                                ) : '';
                        },
                    ],
                ],
            ],
        ]
    );



$js = <<< JS
$(function () {
    $("#eventsList").DataTable({
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
    }).buttons().container().appendTo('#eventsList_wrapper .col-md-6:eq(0)');
  });
JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

