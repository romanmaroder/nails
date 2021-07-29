<?php

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;

//use yii\widgets\ActiveForm;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<!-- Main content -->
<section class="content">
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
                    $form = ActiveForm::begin(); ?>
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
                                'mask'          => '+38(099)999-99-99',
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
                        <?= $form->field($model, 'roles')->checkboxList($model->getRolesDropdown()); ?>

						<div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
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
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
