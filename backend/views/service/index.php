<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Услуги';
$this->params['breadcrumbs'][] = $this->title;

PluginAsset::register($this)->add(
    ['sweetalert2']
);
?>
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
                'cost',
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
