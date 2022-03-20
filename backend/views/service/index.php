<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Услуги';
$this->params['breadcrumbs'][] = $this->title;

PluginAsset::register($this)->add(
    ['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons', 'sweetalert2']
);
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="service-index">

                    <p>
                        <?= Html::a('Добавить услугу', ['create'], ['class' => 'btn btn-success btn-sm']) ?>
                    </p>

                    <?php
                    Pjax::begin(); ?>


                    <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'summary'      => '',
                            'filterModel'  => null,
                            'tableOptions' => [
                                'class' => 'table table-striped table-bordered',
                                'id'    => 'service'
                            ],
                            'columns'      => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'name',
                                [
                                    'attribute' => 'cost',
                                    'value'     => function ($model) {
                                        return Yii::$app->formatter->asCurrency($model->cost);
                                    },
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'format'    => ['date', 'php: d-m-Y']
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                ],
                            ],
                        ]
                    ); ?>

                    <?php
                    Pjax::end(); ?>

                </div>
            </div>
        </div>
    </div>
<?php
$js = <<< JS
$(function () {
$("#service").DataTable({
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
}).buttons().container().appendTo('#statistic_table_wrapper .col-md-6:eq(0)');
});
JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);