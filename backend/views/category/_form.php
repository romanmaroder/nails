<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<!-- left column -->
			<div class="col-md-6">
				<!-- general form elements -->
				<div class="card card-success card-outline">
					<div class="card-header">
						<h3 class="card-title"><?php
                            echo $this->title; ?></h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->
                    <?php
                    $form = ActiveForm::begin(); ?>
					<div class="card-body">
                        <?= $form->field($model, 'category_name')->textInput(['maxlength' => true]) ?>

						<div class="form-group">
                            <?php
                            if ($model->isNewRecord) {
                                echo Html::submitButton('Добавить', ['class' => 'btn btn-outline-success']);
                            } else {
                                echo Html::submitButton('Редактировать', ['class' => 'btn btn-outline-success']);
                            }; ?>

						</div>
					</div>
                    <?php
                    ActiveForm::end(); ?>
				</div>
				<!-- /.card -->
			</div>
			<!--/.col (left) -->

			<!-- right column -->
			<?php if ($model::getCategoryList()) :?>
			<div class="col-md-6">
				<!-- general form elements -->
				<div class="card card-success card-outline">
					<div class="card-header">
						<h3 class="card-title">Категории</h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->

					<div class="card-body">
						<ol>
                            <?php
                            foreach ($model::getCategoryList() as $key=>$category) : ?>
                                <?php
                                echo '<li>'.Html::a($category,['category/update','id'=>$key]).'</li>' ; ?>
                            <?php
                            endforeach; ?>
						</ol>
					</div>

				</div>
				<!-- /.card -->
			</div>
            <?php endif; ?>
			<!--/.col (right) -->
		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->
