<?php

use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Archive */

$this->title                   = $model->service->name;
$this->params['breadcrumbs'][] = ['label' => 'Архив', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="archive-view">

    <!--<h1><?
    /*= Html::encode($this->title) */ ?></h1>

    <p>
        <?
    /*= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) */ ?>
        <?
    /*= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) */ ?>
    </p>-->

    <?= DetailView::widget(
        [
            'model'      => $model,
            'attributes' => [

                [
                    'attribute' => 'user_id',
                    'value'     => function ($model) {
                        return $model->user->username;
                    }
                ],
                [
                    'attribute' => 'service.name'
                ],
                [
                    'attribute' => 'amount',
                    'value'     => function ($model) {
                        return Yii::$app->formatter->asCurrency($model->amount);
                    },
                ],
                'date',
            ],
        ]
    ) ?>

</div>
