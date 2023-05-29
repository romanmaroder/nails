<?php

use common\models\User;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model User */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<?php
Pjax::begin(); ?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title"><?php
                        echo $this->title; ?></h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <?php
                if (Yii::$app->session->hasFlash('danger')): ?>
                    <div class="alert alert-danger alert-dismissible mt-3" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <?php
                        echo Yii::$app->session->getFlash('danger') . '<br><small>Рандомайзер 
                            предложил не уникальную почту.</small>'; ?>
                    </div>
                <?php
                endif; ?>

                <?php
                $form = ActiveForm::begin(
                    [
                        'options' => [
                            'data-pjax' => 1,
                        ],
                    ]
                ); ?>
                <div class="card-body">

                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'status')->dropDownList($model->getStatus()) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'birthday')->widget(
                        DateTimePicker::class,
                        [
                            'options'       => [
                                'placeholder' => 'День рождения',
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

                    <?= $form->field($model, 'phone')->widget(
                        MaskedInput::class,
                        [
                            'mask'          => '+7(999)999-99-99',
                            'options'       => [
                                'class'       => 'form-control',
                                'id'          => 'phone',
                                'placeholder' => ('Телефон')
                            ],
                            'clientOptions' => [
                                'greedy'          => false,
                                'clearIncomplete' => true
                            ]
                        ]
                    ) ?>

                    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'roles')
                        ->checkboxList($model->getRolesDropdown(),['value'=>$model->getRoles('name')]); ?>

                    <?= $form->field($model, 'color')->input(
                        'color', [
                        'class' => 'form-control d-none',
                        'id'    => 'user-color',
                        'value' => $model->profile->color
                    ]
                    )->label(
                        'Цвет',
                        [
                            'class' => 'd-none',
                            'for'   => 'user-color'
                        ]
                    ) ?>

                    <div class="form-group">
                        <?= Html::submitButton(
                            '<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> Сохранить',
                            ['class' => 'btn btn-success']
                        ) ?>
                    </div>

                    <?php
                    ActiveForm::end(); ?>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!--/.col (left) -->
    </div>
    <!-- /.row -->
</div>
<?php
Pjax::end(); ?>