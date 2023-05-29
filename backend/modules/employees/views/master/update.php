<?php


/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Обновить: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'мастера', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'редактировать';
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
