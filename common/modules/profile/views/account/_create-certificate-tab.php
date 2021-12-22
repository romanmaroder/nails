<?php

/* @var $this \yii\web\View */
/* @var $modelCertificate \common\modules\profile\controllers\AccountController */

use common\models\Certificate;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

$models = new Certificate();

$class = get_class($models);


?>
<div class="post-default-index">

    <?php
    $form = ActiveForm::begin(); ?>

    <?php
    echo $form->field($modelCertificate, 'image')->fileInput(['class' => '']); ?>


    <?php
    echo Html::submitButton('Добавить', ['class' => 'btn btn-success']); ?>

    <?php
    ActiveForm::end(); ?>

</div>