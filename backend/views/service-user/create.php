<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceUser */

$this->title = 'Добавить ставку';
$this->params['breadcrumbs'][] = ['label' => 'Ставка мастеров', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-user-create">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
