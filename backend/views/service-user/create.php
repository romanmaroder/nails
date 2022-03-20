<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceUser */

$this->title = 'Добавить ставку';
$this->params['breadcrumbs'][] = ['label' => 'Ставка мастеров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

