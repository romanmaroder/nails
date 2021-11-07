<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
Pjax::begin(); ?>
    <div class="post-search">

        <?php
        $form = ActiveForm::begin(
            [
                    'id'=>'filterPost',
                'action' => ['index'],
                'method' => 'get',
                //'enableAjaxValidation' => true,
                //'validateOnChange' => true,
                'options' => [
                    'data-pjax' => 1,
                    'class' => 'mt-3',
                    'pjax-container'=>''
                ],
                'fieldConfig' => [
                    'options' => ['class' => 'form-group col'],
                    'template' => "<div class='col'>{input}\n{hint}\n{error}</div>"
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
            \common\models\Category::getCategoryList(),
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

        <div class="form-group col">
            <?= Html::submitButton('Поиск', ['class' => 'btn btn-success btn-sm','id'=>'qwe']) ?>
            <!--        --><?
            //= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
        </div>

        <?php
        ActiveForm::end(); ?>

    </div>
<?php
Pjax::end(); ?>
<?php

$js = <<< JS

 $(function () {
     $('input, select').on('change', function(event) {
          $(document).on('submit', 'form[pjax-container]', function(event) {
                        $.pjax.submit(event, '#pjax-container')
})

    });
   
     /*$(document).on('submit', 'form', function(event) {
   $.pjax.submit(event, '[data-pjax-container]')
     
     })*/
     
     /*var form = $("#filterPost");
    form.on("beforeSubmit", function() {
    var data = form.serialize();
    $.ajax({
        "url": form.attr("action"),
        "type": "POST",
        "data": data,
        "success": function (data) {
            // Implement successful
             alert('1321');
        },
        "error": function(jqXHR, errMsg) {
            alert(errMsg);
        }
     });
     return false; // prevent default submit
});*/
 })

JS;
$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);