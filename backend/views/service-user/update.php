<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceUser */

$this->title = 'Редактирование: ' . $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Ставка мастеров', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

