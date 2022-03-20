<?php

use common\models\Expenses;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Expenseslist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expenseslist-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'expenses_id')->dropDownList(
        Expenses::getTitle(),
        ['prompt'=>'Выберите категорию',['class'=>'form-control form-control-sm']]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?/*= $form->field($model, 'created_at')->textInput() */?><!--

    --><?/*= $form->field($model, 'updated_at')->textInput() */?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-sm btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
