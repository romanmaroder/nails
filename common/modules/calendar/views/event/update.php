<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Event */

$this->title = 'Изменить: ' . $model->client->username;
$this->params['breadcrumbs'][] = ['label' => 'клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->client->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'редактировать';
?>
<div class="event-update">

    <h4 style="color:<?= $model->master->color ?>"><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
