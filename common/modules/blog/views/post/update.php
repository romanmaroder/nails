<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = 'Редактировать: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'редактировать';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col">

            <div class="post-update">

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
