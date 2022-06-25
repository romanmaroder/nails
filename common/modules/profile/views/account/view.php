<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Event */

$this->title                   = $model->client->username;
$this->params['breadcrumbs'][] = ['label' => 'записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="container-fluid">
	<div class="row">
		<div class="col-12 col-md-8">
			<div class="card">
				<div class="card-header">
					<h3 class="card-title"><?= Html::encode('Запись: '.$model->client->username) ?></h3>
				</div>
				<!-- /.card-header -->
				<div class="card-body">
                    <?= DetailView::widget(
                        [
                            'model'      => $model,
                            'options'    => [
                                'class' => 'table table-striped'
                            ],
                            'attributes' => [
                                [
                                    'attribute' => 'client_id',
                                    'format'    => 'raw',
                                    'visible'   => Yii::$app->user->can('master'),
                                    'value'     => function ($client) {
                                        return Html::a(
                                            $client->client->username,
                                            ['/client/client/view', 'id' => $client->client->id]
                                        );
                                    }
                                ],
                                [
                                    'attribute' => 'master_id',
                                    'value'     => function ($master) {
                                        return $master->master->username;
                                    }
                                ],
                                [
                                    'attribute' => 'description',
                                    'format'=>'raw',
                                    'visible' => $model->description ?? '',
                                ],
                                [
                                    'attribute' => 'service_array',
                                    'format'=>'raw',
                                    'value' => function ($data) {
                                        return \common\models\Event::getServiceName($data->services);
                                    },
                                    'visible' => $model->services ?? '',
                                ],
                                [
                                    'attribute' => 'notice',
                                    'visible'   => Yii::$app->user->can('master'),

                                ],
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
				<!-- /.card-body -->
				<!--<div class="card-footer">-->
					<!--<p>-->
                        <?/*= Html::a(
                                            'Редактировать',
                                            ['/calendar/event/update', 'id' => $model->id],
                                            [
                                                'id'      => 'edit-link',
                                                'onClick' => "$('#modal').find('.modal-body').load($(this).attr('href')); return false;",
                                                'class'   => 'btn btn-primary btn-sm'
                                            ]
                                        )  */?><!--
                        --><?/*= Html::a(
                                            'Удалить',
                                            ['delete', 'id' => $model->id],
                                            [
                                                'class' => 'btn btn-danger btn-sm',
                                                'data'  => [
                                                    'confirm' => 'Удалить эту паскуду?',
                                                    'method'  => 'post',
                                                ],
                                            ]
                                        )  */?>
					<!--</p>-->
				<!--</div>-->
			</div>
		</div>

	</div>
</div>
