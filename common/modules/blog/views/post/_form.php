<?php

use common\models\Category;
use hail812\adminlte3\assets\PluginAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */

PluginAsset::register($this)->add(
    ['summernote', 'codemirror']
);
?>


	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<!-- left column -->
				<div class="col">
					<!-- general form elements -->
					<div class="card card-success card-outline">
						<div class="card-header">
							<h3 class="card-title"><?php
                                echo $this->title; ?></h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
                        <?php
                        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
						<div class="card-body">


                            <?= $form->field($model, 'category_id')->dropDownList(
                                Category::getCategoryList(),
                                [
                                    'prompt' => [
                                        'text'    => 'Выберите категорию',
                                        'options' => [
                                            'value' => 'none',
                                            'class' => 'prompt',
                                            'label' =>
                                                'Выберите категорию'
                                        ]
                                    ],
                                ]
                            ) ?>

                            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'description')->textarea(['rows' => 6, 'id' => 'summernote']) ?>

							<div class="form-group">
                                <?= Html::submitButton('Добавить', ['class' => 'btn btn-outline-success']) ?>
							</div>
						</div>
                        <?php
                        ActiveForm::end(); ?>
					</div>
					<!-- /.card -->
				</div>
				<!--/.col (left) -->
			</div>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->


<?php

Yii::$app->view->registerJs("url= " .Json::htmlEncode(Url::base()), View::POS_HEAD);


echo Url::base();

$editor = <<< JS
$(function () {
    // Summernote
    $('#summernote').summernote({
     lang: 'ru-RU',
     placeholder: 'Добавить статью',
     codemirror: {
      mode: 'text/html'
    },
     callbacks: {
        onImageUpload: function(files, editor, welEditable) {
				  sendFile(files[0], editor, welEditable);
				},
				
		onMediaDelete : function(target) {
		deleteFile(target[0].src);
		}
    }
    });
    
   function sendFile(file) {
        
    let data = new FormData();
    data.append("file", file);
console.log(url)
    $.ajax({
      data: data,
      type: "POST",
      url: url + "/blog/post/upload-image-summernote/",
      cache: false,
      contentType: false,
      processData: false,
      success: function(responce) {
          var image = $('<img>').attr('src', responce.uri);
            $('#summernote').summernote("insertNode", image[0]);
      },
      error: function(responce) {
            alert(responce.message);
        }
    });
  }
  
  function deleteFile(src) {

    $.ajax({
        data: {src : src},
        type: "POST",
        url: url +"/blog/post/delete-image-summernote/", // replace with your url
        cache: false,
        success: function(resp) {
            console.log(resp.message);
           
        }
    });
}
  });
JS;

$this->registerJs($editor, $position = yii\web\View::POS_READY, $key = null);
?>