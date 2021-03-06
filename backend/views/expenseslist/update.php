<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Expenseslist */

$this->title = 'Редактировать: ' . $model->expenses->title;
$this->params['breadcrumbs'][] = ['label' => 'Затраты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>


<div class="container-fluid">
    <div class="row">
        <div class="col">

            <div class="expenseslist-update">

                <h1><?= Html::encode($this->title) ?></h1>

                <?= $this->render(
                    '_form',
                    [
                        'model' => $model,
                    ]
                ) ?>

            </div>
        </div>
    </div>
</div>
