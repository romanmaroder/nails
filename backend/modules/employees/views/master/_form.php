<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
				<!-- general form elements -->
				<div class="card card-success">
					<div class="card-header">
						<h3 class="card-title"><?php echo $this->title ;?></h3>
					</div>
					<!-- /.card-header -->
					<!-- form start -->

                    <?php $form = ActiveForm::begin(); ?>
					<div class="card-body">
                        <?= $form->field($model, 'status')->dropDownList($model->getStatus()) ?>
                        <?= $form->field(
                            $model,
                            'color',
                            [
                                'template' => "{input}"
                            ]
                        )->input('color', ['class' => "form-control"]) ?>

                        <?= $form->field($model, 'roles')->checkboxList($model->getRolesDropdown()) ?>

						<div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
						</div>


                        <?php ActiveForm::end(); ?>
					</div>
				</div>
				<!-- /.card -->
			</div>
			<!--/.col (left) -->
		</div>
		<!-- /.row -->
	</div><!-- /.container-fluid -->
</section>
<!-- /.content -->

