<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-user-search">

    <?php $form = ActiveForm::begin([
       // 'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'service_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'rate') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-sm btn-primary']) ?>
        <?/*= Html::resetButton('Сбросить', ['class' => 'btn btn-sm btn-outline-secondary']) */?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
