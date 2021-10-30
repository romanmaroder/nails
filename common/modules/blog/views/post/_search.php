<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'mt-3',
        ],
        'fieldConfig' => ['options' => ['class' => 'form-group col'],
                          'template' => "<div class='col'>{input}\n{hint}\n{error}</div>"],
    ]); ?>

    <?/*= $form->field($model, 'id') */?>

    <?= $form->field($model, 'user_id')->dropDownList(\common\models\Post::getAuthorPostList(),['prompt' => 'По автору',
		]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(\common\models\Category::getCategoryList(),['prompt' => 'По категории',]) ?>

    <?/*= $form->field($model, 'title') */?>

    <?/*= $form->field($model, 'subtitle') */?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group col">
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-success btn-sm']) ?>
<!--        --><?//= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
