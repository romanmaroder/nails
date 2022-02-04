<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Expenseslist */

$this->title = 'Добавить затраты';
$this->params['breadcrumbs'][] = ['label' => 'Затраты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenseslist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
