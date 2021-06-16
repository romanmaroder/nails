<?php

//use common\models\Client;
//use common\models\Master;
use common\models\User;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;

//use yii\widgets\ActiveForm;/*/
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Event */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="event-form">
    <?php

    $form = ActiveForm::begin(
        [
            'id' => 'event-form',
//            'enableAjaxValidation'   => true,
//            'enableClientValidation' => true,
            /*'validateOnChange'       => true,
            'validateOnBlur'         => true*/
        ]
    ); ?>

    <?= $form->field($model, 'event_time_start')->widget(
        DateTimePicker::class,
        [
            'options'       => [
                'placeholder' => 'Начало события ...',
                'type'        => 'text',
                'readonly'    => true,
                'class'       => 'text-muted small',
                'style'       => 'border:none;background:none'
            ],
            'type'          => DateTimePicker::TYPE_BUTTON,
            'layout'        => '{picker} {remove} {input}',
            'pickerIcon'    => '<i class="fa fa-calendar"></i>',
            'removeIcon'    => '<i class="fa fa-times"></i>',
            'pluginOptions' => [
                'autoclose' => true,
//                'todayHighlight' => true,
//                'todayBtn'       => true,
//                'startDate'      => date('Y-m-d'),
            ],

            'language' => 'ru',
            'size'     => 'xs'
        ]
    ) ?>

    <?= $form->field($model, 'event_time_end')->widget(
        DateTimePicker::class,
        [
            'options'       => [
                'placeholder' => 'Конец события ...',
                'type'        => 'text',
                'readonly'    => true,
                'class'       => 'text-muted small',
                'style'       => 'border:none;background:none'
            ],
            'type'          => DateTimePicker::TYPE_BUTTON,
            'layout'        => '{picker} {remove} {input}',
            'pickerIcon'    => '<i class="fa fa-calendar"></i>',
            'removeIcon'    => '<i class="fa fa-times"></i>',
            'pluginOptions' => [
                'autoclose' => true,
//                'todayHighlight' => true,
//                'todayBtn'       => true,
//                'startDate'      => date('Y-m-d'),
            ],
            'language'      => 'ru',
            'size'          => 'xs'

        ]
    ) ?>

    <?= $form->field($model, 'client_id')->dropDownList(
        User::getClientList(),
        [
            'prompt' => [
                'text'    => 'Выберите мастера',
                'options' => [
                    'value' => 'none',
                    'class' => 'prompt',
                    'label' =>
                        'Выберите клиента'
                ]
            ],
        ]
    ) ?>

    <?= $form->field($model, 'master_id', ['validateOnChange' => true])->dropDownList(
        User::getMasterList(),
        [
            'prompt' => [
                'text'    => 'Выберите мастера',
                'options' => [
                    'value' => 'none',
                    'class' => 'prompt',
                    'label' =>
                        'Выберите мастера'
                ]
            ],
        ]
    ) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'notice')->textInput(['maxlength' => true]) ?>


	<div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
	</div>

    <?php
    ActiveForm::end(); ?>

</div>
