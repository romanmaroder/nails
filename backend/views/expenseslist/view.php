<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Expenseslist */

$this->title = $model->expenses->title;
$this->params['breadcrumbs'][] = ['label' => 'Затраты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
PluginAsset::register($this)->add(
    ['sweetalert2']
);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="expenseslist-view">

                <h1><?= Html::encode($this->title) ?></h1>

                <p>
                    <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
                    <?= Html::a(
                        'Удалить',
                        ['delete', 'id' => $model->id],
                        [
                            'class' => 'btn btn-sm btn-danger',
                            'data'  => [
                                'confirm' => 'Удалить эти затраты?',
                                'method'  => 'post',
                            ],
                        ]
                    ) ?>
                </p>

                <?= DetailView::widget(
                    [
                        'model'      => $model,
                        'attributes' => [
                            //'id',
                            //'expenses_id',
                            [
                                'attribute' => 'expenses_id',
                                'value'     => function ($model) {
                                    return $model->expenses->title;
                                }
                            ],
                            [
                                'attribute' => 'price',
                                'value'     => function ($model) {
                                    return Yii::$app->formatter->asCurrency($model->price);
                                }
                            ],
                            [
                                'attribute' => 'created_at',
                                'format'    => ['date', 'php: d-m-Y']
                            ],
                            //'updated_at',
                        ],
                    ]
                ) ?>

            </div>
        </div>
    </div>
</div>