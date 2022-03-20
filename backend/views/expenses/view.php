<?php

use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Expenses */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Расходы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
PluginAsset::register($this)->add(
    ['sweetalert2']
);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="expenses-view">

                <h1><?= Html::encode($this->title) ?></h1>

                <p>
                    <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?= Html::a(
                        'Удалить',
                        ['delete', 'id' => $model->id],
                        [
                            'class' => 'btn btn-danger btn-sm',
                            'data'  => [
                                'confirm' => 'Удалить эту статью расходов?',
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
                            'title',
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
</div>