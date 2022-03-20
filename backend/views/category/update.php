<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->title = 'редактировать: ' . $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->category_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'редактировать';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="category-update">

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
