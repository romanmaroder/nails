<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title                   = 'Добавить клиента';
$this->params['breadcrumbs'][] = ['label' => 'клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render(
    '_form',
    [
        'model' => $model,
    ]
) ?>


