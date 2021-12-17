<?php

use common\modules\todo\controllers\TodoController;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Todo */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider TodoController */
?>

<?php

$this->registerJs(
    '
    
		$("#new_todo").on("pjax:end", function() {
			$.pjax.reload({container:"#todo-list"});  //Reload ListdView
			
		});
		$("#pager").on("pjax:success", function() {
			 $.pjax.reload({container:"#todo-list"}); 
			
		});
		
		'
    ,yii\web\View::POS_LOAD);
?>
<div class="card">
    <div class="card-header ui-sortable-handle" style="cursor: move;">
        <h3 class="card-title">
            <i class="ion ion-clipboard mr-1"></i>
            Заметки
        </h3>

        <div class="card-tools">
            <?php Pjax::begin(['id' => 'pager', 'options' => ['class' => '']]); ?>
            <?=
                ListView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'options' => [
                        'tag' => false,
                    ],
                    'layout' => "{pager}",
                    'pager' => [
                        'prevPageLabel' => '<i class="fas fa-angle-double-left"></i>',
                        'nextPageLabel' => '<i class="fas fa-angle-double-right"></i>',

                        'options' => [
                            'tag' => 'ul',
                            'class' => 'pagination pagination-sm',

                        ],
                        'maxButtonCount'=>20,
                        'linkContainerOptions' => [

                            'class' => 'page-item'
                        ],
                        'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                        'linkOptions' => ['class' => 'page-link'],
                    ],
                ]
            );
            ?>
            <?php Pjax::end(); ?>
        </div>

    </div>
    <!-- /.card-header -->

    <div class="card-body">

<?php  Pjax::begin(['id' => 'new_todo','options' => ['class'=>'row mb-3']])?>
        <div class="col-12 col-sm-9 mb-2 mb-sm-0">
    <?php $form = ActiveForm::begin( ['options' => ['data-pjax' => true ],
                                         'id'=>'todo-form',
                                         'method' => 'post',
                                         'fieldConfig' => [
                                             'options' => [
                                                 'tag' => false,
                                             ]
                                         ]
                                         ]); ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->getId()])->label(
        false) ?>

    <?= $form->field($model, 'title')->textInput([
                                                     'class' => 'form-control',
                                                     'maxlength' => true,
                                                     'placeholder' => 'Добавить заметку',
                                                 ])->label(false) ?>

    <?= $form->field($model, 'status')->checkbox(['class' => 'd-none', 'label' => null, 'uncheckValue' =>  0]) ?>
            <?php ActiveForm::end(); ?>

        </div>
    <div class="col-12 col-sm-3 text-right">
        <?= Html::submitButton('<i class="fas fa-plus"></i> Добавить', ['class' => 'btn btn btn-primary','form' => 'todo-form']) ?>
    </div>
<?php  Pjax::end();?>




