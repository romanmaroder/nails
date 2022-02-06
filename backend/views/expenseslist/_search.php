<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ExpenseslistSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expenseslist-search">

    <?php $form = ActiveForm::begin([
        //'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>


    <?= $form->field($model, 'expenses_id')->dropDownList(
        \common\models\Expenses::getTitle(),
        ['prompt' => 'Выберите статью расходов..','class'=>'form-control form-control-sm']) ?>

    <?/*= $form->field($model, 'price') */?>

    <?= $form->field($model, 'date_from')->widget(
        DatePicker::class,
        [
            'model'         => $model,
            'attribute'     => 'date_from',
            'attribute2'    => 'date_to',
            'type'          => kartik\date\DatePicker::TYPE_RANGE,
            'size'          => 'sm',
            'separator'     => 'по',
            'options'=>[
                'autocomplete'=>'off',
            ],
            'pluginOptions' => [
                'todayHighlight' => true,
                'weekStart'      => 1, //неделя начинается с понедельника
                'autoclose'      => true,
                'orientation'    => 'bottom auto',
                'clearBtn'       => true,
                'todayBtn'       => 'linked',
                'format'         => 'yyyy-mm-dd',
            ]
        ]
    )->label('Дата') ?>

    <div class="form-group">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-sm btn-primary']) ?>
        <?/*= Html::resetButton('Reset', ['class' => 'btn btn-sm btn-outline-secondary']) */?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
