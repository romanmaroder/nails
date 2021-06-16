<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Event */

$this->title                   = $model->client->username;
$this->params['breadcrumbs'][] = ['label' => 'записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="event-view">

<!--	<h3 style="color:--><?//= $model->master->color ?><!--">--><?//= Html::encode($this->title) ?><!--</h3>-->
	<h3 ><?= Html::a($model->client->username,['client/client/view','id'=>$model->client->id],['style'=>'color:'
			.$model->master->color]) ?></h3>


    <?= DetailView::widget(
        [
            'model'      => $model,
            'attributes' => [
                [
                    'attribute' => 'master_id',
                    'format'    => 'raw',
                    'value'     => function ($data) {
                        return '<span style="color: '.$data->master->color.'">'.$data->master->username.'</p>';
                    }
                ],
                'description:ntext',
                'notice',
//                'event_time_start',
                [
                    'attribute' => 'event_time_start',
                    'label'     => 'Дата',
                    'format'    => ['date', 'php:d-m-Y'],
                ],
                [
                    'attribute' => 'event_time_start',
                    'label'     => 'Время',
                    'format'    => ['date', 'php:H:i'],
                ]
            ],
        ]
    ) ?>
</div>
