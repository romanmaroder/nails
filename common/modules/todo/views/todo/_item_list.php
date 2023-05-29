<?php

use common\modules\todo\controllers\TodoController;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

/* @var $model TodoController */
/* @var $key TodoController */
/* @var $index TodoController */

$form = ActiveForm::begin(
    [
        'options' => ['data-pjax' => true],
        'id' => $model->id,
        'method' => 'post',
        'action' => Url::to(['/todo/todo/update/', 'id' => $model->id]),
        'fieldConfig' => [
            'options' => [
                'tag' => false,
            ]
        ]
    ]
);

?>


    <!-- drag handle -->
    <span class='handle ui-sortable-handle'>
  <i class='fas fa-ellipsis-v'></i>
  <i class='fas fa-ellipsis-v'></i>
</span>
    <!-- checkbox -->
    <div class='icheck-primary d-inline ml-2'>


        <?= $form->field($model, 'status')->checkbox(
            [
                'class' => '',
                'id' => $model->id,
                #'uncheck' => $model->status ? '0' : null,
                'uncheck' => null,
                'checked' => $model->status ? true : false,
                'value' => $model->status ? '0' : '1',
                'template' => '{input}{label}'
            ]
        )->label(" ", ['class' => '']); ?>

    </div>
    <!-- todo text -->
    <span class='text'>
    <?= $form->field($model, 'title')->textInput(
        [
            'class' => 'text no-input-style',
            'id' => $model->id,
        ]
    )->label(false); ?>
</span>
<span class="text text-title"  id ="<?=$model->id?>"><?= $model->title?></span>
    <!-- Emphasis label -->
    <small class='badge badge-danger'>
        <i class='far fa-clock'></i>
        <?= Yii::$app->formatter->asRelativeTime($model->created_at); ?></small>
    <!-- General tools such as edit or delete-->
    <div class='tools'>
        <i class='fas fa-edit' data-id='<?= $model->id; ?>'></i>
        <i class='fas fa-trash' data-id='<?= $model->id; ?>'></i>
    </div>


<?php ActiveForm::end(); ?>

