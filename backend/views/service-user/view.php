<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceUser */

$this->title = $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Ставка мастеров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
PluginAsset::register($this)->add(
    ['sweetalert2']
);
?>
<div class="service-user-view">

   <!-- <h1><?/*= Html::encode($this->title) */?></h1>-->

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-sm btn-danger',
            'data' => [
                'confirm' => 'Хотите удалить ставку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
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
        ],
    ]) ?>

</div>
