<?php

use common\models\Service;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceUser */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if (Yii::$app->session->hasFlash('warning')): ?>
    <div class="alert alert-warning alert-dismissible mt-3" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo Yii::$app->session->getFlash('warning') . '<br><small>Выберите другую услугу</small>'; ?>
    </div>
<?php endif; ?>

<?php Pjax::begin(); ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="service-user-form">

                    <?php $form = ActiveForm::begin(
                        [
                            'options' => [
                                'data-pjax' => 1,
                            ],
                        ]
                    ); ?>

                    <?= $form->field($model, 'service_id')->widget(
                        Select2::class,
                        [
                            'name'          => 'service_array',
                            'language'      => 'ru',
                            'data'          => Service::getServiceList(),
                            'theme'         => Select2::THEME_MATERIAL,
                            'options'       => [
                                'placeholder'  => 'Выберите услугу ...',
                                'multiple'     => false,
                                'autocomplete' => 'off',
                            ],
                            'pluginOptions' => [
                                'tags'       => true,
                                'allowClear' => true,
                            ],
                        ]
                    ) ?>

                    <?= $form->field($model, 'user_id')->widget(
                        Select2::class,
                        [
                            'name'          => 'master',
                            'language'      => 'ru',
                            'data'          => User::getMasterList(),
                            'options'       => ['placeholder' => 'Выберите мастера ...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]
                    ) ?>

                    <?= $form->field($model, 'rate')->textInput() ?>

                    <? /*= $form->field($model, 'created_at')->textInput() */ ?>

                    <? /*= $form->field($model, 'updated_at')->textInput() */ ?>

                    <div class="form-group">
                        <?= Html::submitButton('<span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> Сохранить', ['class' => 'btn btn-sm btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>