<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Редактировать: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'редактировать';
?>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

