<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Expenses */

$this->title = 'Добавить расходы';
$this->params['breadcrumbs'][] = ['label' => 'Расходы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="expenses-create">

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
