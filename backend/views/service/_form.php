<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Service */
/* @var $form yii\widgets\ActiveForm */
?>
<?php Pjax::begin(); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="service-form">

                    <?php $form = ActiveForm::begin(
                        [
                            'options' => [
                                'data-pjax' => 1,
                            ],
                        ]
                    ); ?>

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'cost')->textInput() ?>

                    <? /*= $form->field($model, 'created_at')->textInput() */ ?>

                    <? /*= $form->field($model, 'updated_at')->textInput() */ ?>

                    <div class="form-group">
                        <?= Html::submitButton('<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> Сохранить', ['class' => 'btn btn-success btn-sm']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>