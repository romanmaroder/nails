<?php

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="post-search">

        <?php
        $form = ActiveForm::begin(
            [
            	'id'=>'filterPost',
                'action' => ['index'],
                'method' => 'get',
                'options' => [
                    'data-pjax' => 0,
                    'class' => 'row mt-2',
                    'pjax-container'=>'pjax-container'
                ],
                'fieldConfig' => [
                    'options' => ['class' => 'form-group col-12 col-sm-6 col-xl-12'],
                    'template' => "<div class=''>{input}\n{hint}\n{error}</div>"
                ],
            ]
        ); ?>

        <?
        /*= $form->field($model, 'id') */ ?>

        <?= $form->field($model, 'user_id'/*,['inputOptions'=>['name'=>'author','class'=>'form-control']]*/)
->dropDownList(
            \common\models\Post::getAuthorPostList(),
            [
                'prompt' => 'По автору',
            ]
        ) ?>

        <?= $form->field($model, 'category_id'/*,['inputOptions'=>['name'=>'category','class'=>'form-control']]*/)
			->dropDownList(
            \common\models\Category::getCategoryPostList(),
            ['prompt' => 'По категории',]
        ) ?>

        <?
        /*= $form->field($model, 'title') */ ?>

        <?
        /*= $form->field($model, 'subtitle') */ ?>

        <?php
        // echo $form->field($model, 'description') ?>

        <?php
        // echo $form->field($model, 'created_at') ?>

        <?php
        // echo $form->field($model, 'updated_at') ?>

        <?php
        ActiveForm::end(); ?>

    </div>

