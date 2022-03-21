<?php

//use common\models\Client;
//use common\models\Master;
use common\models\Event;
use common\models\Service;
use common\models\ServiceUser;
use common\models\User;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\Html;

//use yii\widgets\ActiveForm;/*/
use yii\bootstrap4\ActiveForm;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Event */
/* @var $form yii\bootstrap4\ActiveForm */


?>

<div class="event-form">
    <?php

    $form = ActiveForm::begin(
        [
            'id'                     => 'event-form',
            'enableAjaxValidation'   => false,
            'enableClientValidation' => true,
            'validateOnChange'       => true,
            'validateOnBlur'         => false
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

    <?= $form->field($model, 'client_id')->widget(
        Select2::class,
        [
            'name'          => 'client',
            'language'      => 'ru',
            'data'          => User::getClientList(),
            'options'       => ['placeholder' => 'Выберите клиента ...'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]
    ) ?>

    <?= $form->field($model, 'master_id')->widget(
        Select2::class,
        [
            'name'          => 'master',
            'language'      => 'ru',
            'data'          => User::getMasterList(),
            'options'       => ['placeholder' => 'Выберите мастера ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents'  => [
                "change" => 'function() { 
                var data_id = $(this).val();
                $.ajax({
                    url: "/calendar/event/user-service",
                    method: "get",
                    dataType: "json",
                    data: {id: data_id},
                    success: function(data){
                       $("select#serviceUser").html(data).attr("disabled", false);
                    },
                    error: function(data , jqXHR, exception){
                        var Toast = Swal.mixin({
                                          toast: true,
                                          position: "top-end",
                                          showConfirmButton: false,
                                          timer: 5000,
                                        });
                          Toast.fire({
                            icon: "error",
                            //title: data.responseJSON.message
                            title: data.responseText
                          });
                    }
                });
                }',
            ],
        ]
    ); ?>


    <?= $form->field($model, 'service_array')->widget(
        Select2::class,
        [
            'name'          => 'service_array[]',
            'language'      => 'ru',
            'data'          => ServiceUser::getUserServices($model->master->id),
            'theme'         => Select2::THEME_MATERIAL,
            'options'       => [
                'id'           => 'serviceUser',
                'placeholder'  => 'Выберите услугу ...',
                'multiple'     => true,
                'autocomplete' => 'off',
            ],
            'pluginOptions' => [
                'tags'       => true,
                'allowClear' => true,
            ],
        ]
    ) ?>
    <?
    /*= $form->field($model, 'description')->textarea(['rows' => 3]) */ ?>

    <?= $form->field($model, 'notice')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'checkEvent', ['enableAjaxValidation' => true])->label(false)
        ->hiddenInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
    </div>
    <?php
    ActiveForm::end(); ?>

</div>
