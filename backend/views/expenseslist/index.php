<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ExpenseslistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Затраты';
$this->params['breadcrumbs'][] = $this->title;
PluginAsset::register($this)->add(
    ['sweetalert2']
);
?>
<div class="expenseslist-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить затраты', ['create'], ['class' => 'btn btn-sm btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'expenses_id',
                'value'     => function ($model) {
                    return $model->expenses->title;
                }
            ],
            'price',
            [
                'attribute' => 'created_at',
                'format'    => ['date', 'php: d-m-Y']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
