<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

PluginAsset::register($this)->add(['sweetalert2']);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="service-view">

                <p>
                    <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary ']) ?>
                    <?= Html::a(
                        'Удалить',
                        ['delete', 'id' => $model->id],
                        [
                            'class' => 'btn btn-danger',
                            'data'  => [
                                'confirm' => 'Удалить услугу?',
                                'method'  => 'post',
                            ],
                        ]
                    ) ?>
                </p>

                <?= DetailView::widget(
                    [
                        'model'      => $model,
                        'attributes' => [
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
                        ],
                    ]
                ) ?>

            </div>
        </div>
    </div>
</div>