<?php

use common\modules\todo\controllers\TodoController;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Todo */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider TodoController */
?>

<?php
//Подключение скриптов для отправки, редактирования, удаления записей
$this->registerJsFile(
    '@web/js/todo.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);


;?>
<div class="card">
    <div class="card-header ui-sortable-handle" style="cursor: move;">
        <h3 class="card-title">
            <i class="ion ion-clipboard mr-1"></i>
            Заметки
        </h3>

        <div class="card-tools">
                <?php Pjax::begin(['id' => 'pager',
                    'enableReplaceState'=>true,
                    'options' => ['class' => ''],
                    'enablePushState' => false, // to disable push state
                    //'enableReplaceState' => false, // to disable replace state,
                    'timeout'=> 1000,
                    'clientOptions' => ['method' => 'POST']]); ?>
                <!--Пагинация-->
            <?= ListView::widget(
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
                        'linkOptions' => ['class' => 'page-link','data-pjax'=>1],
                    ],
                    'emptyText' => '',
                    'emptyTextOptions' => [
                        'tag' => 'div',
                        'class' => 'col-12 col-lg-6 mb-3 text-info '
                    ],
                ]
            );
            ?>
            <?php Pjax::end(); ?>
        </div>

    </div>
    <!-- /.card-header -->

    <div class="card-body">

<?php  Pjax::begin(['id' => 'new_todo', 'enablePushState'=>false,'options' => ['class'=>'row mb-3'],'clientOptions' => ['method' => 'POST']])?>
        <div class="col-12 col-sm-10 mb-2 mb-sm-0">
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

    <?= $form->field($model, 'status')->checkbox(['class' => 'd-none', 'uncheckValue' =>  0])->label(false) ?>
            <?php ActiveForm::end(); ?>

        </div>
    <div class="col-12 col-sm-2 text-right">
        <?= Html::submitButton('<i class="fas fa-plus"></i> Добавить', ['class' => 'btn btn btn-primary','form' => 'todo-form']) ?>
    </div>
<?php  Pjax::end();?>




