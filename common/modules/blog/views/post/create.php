<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = 'Новая статья';
$this->params['breadcrumbs'][] = ['label' => 'статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="post-create">
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
