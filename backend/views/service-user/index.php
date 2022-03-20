<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ServiceUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ставка мастеров';
$this->params['breadcrumbs'][] = $this->title;
PluginAsset::register($this)->add(
    ['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons','sweetalert2']
);
?>
<div class="service-user-index">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'summary' => false,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered',
            'id' => 'serviceUser'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'service.id',
            'service.name',
            'user.username',
            'rate',
            [
                'attribute' => 'created_at',
                'label'     => 'Дата',
                'format'    => ['date', 'php:d-m-Y'],
            ],
            /*[
                'attribute' => 'updated_at',
                'label'     => 'Дата',
                'format'    => ['date', 'php:d-m-Y'],
            ],*/

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
<?php
$js = <<< JS
$(function () {
$("#serviceUser").DataTable({
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