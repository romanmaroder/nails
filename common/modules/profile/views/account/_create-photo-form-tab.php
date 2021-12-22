<?php

use common\models\User;
use common\modules\profile\models\AddPhotoForm;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $modelPhoto AddPhotoForm */
?>
<div class="post-default-index">

    <?php
    $form = ActiveForm::begin(); ?>

    <?php
    echo $form->field($modelPhoto, 'picture')->fileInput(['class' => '']); ?>
    <?php
    echo $form->field($modelPhoto, 'client_check')->checkbox(); ?>

	<?php
   echo $form->field($modelPhoto, 'client')->widget(
        Select2::class,
        [
            'name'          => 'client',
            'language'      => 'ru',
            'data'          =>  User::getClientList(),
            'options'       => [
            		'placeholder' => 'Выберите клиента ...',
                    'class'       => 'client-photo',
			],
            'pluginOptions' => [

                'allowClear' => true
            ],
        ]
    )->label(null, ['class' => 'd-none']); ?>
    <?php
    echo $form->field($modelPhoto, 'master_work')->checkbox(); ?>
    <?php
    echo $form->field($modelPhoto, 'portfolio')->checkbox(); ?>

    <?php
    echo Html::submitButton('Добавить', ['class' => 'btn btn-success']); ?>

    <?php
    ActiveForm::end(); ?>

</div>
