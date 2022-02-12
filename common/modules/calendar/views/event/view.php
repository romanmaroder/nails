<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Event */

$this->title                   = $model->client->username;
$this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
PluginAsset::register($this)->add(['sweetalert2']);
?>
<div class="event-view">

    <h3><?= Html::a(
            $model->client->username,
            ['/client/client/view', 'id' => $model->client->id],
            [
                'style' => 'color:'
                    . $model->master->color
            ]
        ) ?></h3>


    <?= DetailView::widget(
        [
            'model'      => $model,
            'attributes' => [
                [
                    'attribute' => 'master_id',
                    'format'    => 'raw',
                    'value'     => function ($data) {
                        return '<span style="color: ' . $data->master->color . '">' . $data->master->username . '</p>';
                    }
                ],
                [
                    'attribute' => 'description',
                    'format'=>'ntext',
                    'visible' => $model->description ?? '',
                ],
                //'serviceString',
                [
                    'attribute' => 'service_array',
                    'format'=>'raw',
                    'value' => function ($data) {
                        return \common\models\Event::getServiceString($data->services);
                    },
                    'visible' => $model->services ?? '',
                ],
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
    <?php
    if (Yii::$app->id == 'app-backend'): ?>
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
            <?php

            $options = [
                'class' => 'btn btn-info btn-sm d-none',
                'href'  => 'sms:' . $model->client->phone . Yii::$app->smsSender->checkOperatingSystem(
                    ) . Yii::$app->smsSender->messageText(
                        $model->event_time_start
                    ),
                'title' => 'Отправить смс',
            ];

            if ($model->client->phone) {
                Html::removeCssClass($options, 'd-none');
                Html::addCssClass($options, 'd-in;ine-block');
            }
            echo Html::tag('a', '<i class="far fa-envelope"></i>', $options);
            ?>
            <?php

            $options = [
                'class' => 'btn btn-info btn-sm d-none',
                'href'  => 'sms:' . $model->client->phone . Yii::$app->smsSender->checkOperatingSystem(
                    ) . Yii::$app->smsSender->messageAddress(),
                'title' => 'Отправить адрес',
            ];

            if ($model->client->phone) {
                Html::removeCssClass($options, 'd-none');
                Html::addCssClass($options, 'd-in;ine-block');
            }
            echo Html::tag('a', '<i class="fas fa-map-marker-alt"></i>', $options);
            ?>

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
    <?php
    endif; ?>
</div>


