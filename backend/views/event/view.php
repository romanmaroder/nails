<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Event */

$this->title                   = $model->client->username;
$this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="event-view">

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
	<p>
        <?= Html::a(
            'Редактировать',
            ['update', 'id' => $model->id],
            [
                'id'      => 'edit-link',
                'onClick' => "$('#view').find('.modal-body').load($(this).attr('href')); return false;",
                'class'   => 'btn btn-primary btn-sm'
            ]
        ) ?>
        <?= Html::a(
            Yii::t('app', 'Удалить'),
            ['delete', 'id' => $model->id],
            [
                'id'    => 'delete',
                'class' => 'btn btn-danger btn-sm',
                'data'  => [
                    'confirm' => Yii::t('app', 'Удалить запись?'),
                    'method'  => 'post',
                ],
            ]
        ) ?>
	</p>

</div>
