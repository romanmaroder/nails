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
                    'data-pjax' => 1,
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

        <?= $form->field($model, 'user_id')->dropDownList(
            \common\models\Post::getAuthorPostList(),
            [
                'prompt' => 'По автору',
            ]
        ) ?>

        <?= $form->field($model, 'category_id')->dropDownList(
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

<?php

$js = <<< JS

 $(function () {
     $(document).on('change','#filterPost', function(event) {
     $('form[pjax-container]').submit();
    
    });
   
 })

JS;
$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);