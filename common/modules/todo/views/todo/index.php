<?php

use common\modules\todo\controllers\TodoController;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TodoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model TodoController */

$this->title = 'Заметки';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
#Регистрация переменных для использования в js коде

Yii::$app->view->registerJs(
    "app=" . Json::encode(Yii::$app->id) . "; basePath=" . Json::encode(Yii::$app->request->baseUrl) . ";",
    View::POS_HEAD
); ?>
<section class="content">
    <div class="todo-index">

        <!-- Render create form -->
        <?= $this->render(
            '_form',
            [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]
        ) ?>


        <?php Pjax::begin(['id' => 'todo-list', 'options' => ['class' => 'row']]); ?>

        <div class="col-12">
            <?php $form = ActiveForm::begin(
                [
                    'options' => ['data-pjax' => true],
                    'id' => 'index-todo-form',
                    'method' => 'post',
                    'fieldConfig' => [
                        'options' => [
                            'tag' => false,
                        ]
                    ]
                ]
            ); ?>
            <?= ListView::widget(
                [
                    'dataProvider' => $dataProvider,
                    'options' => [
                        'tag' => 'ul',
                        'class' => 'todo-list ui-sortable col-12',
                        'data-widget' => 'todo-list',
                        'id' => 'my-todo-list'
                    ],
                    'layout' => "{items}",
                    'itemOptions' => ['tag' => 'li'],
                    'itemView' => function ($model, $key, $index) use ($form) {
                        return "<!-- drag handle -->
                                            <span class='handle ui-sortable-handle'>
                                              <i class='fas fa-ellipsis-v'></i>
                                              <i class='fas fa-ellipsis-v'></i>
                                            </span>
                                            <!-- checkbox -->
                                            <div class='icheck-primary d-inline ml-2'>
                                           
                                            
                                           {$form->field($model, 'status')->checkbox(
                                                                            [
                                                                            'class' => '',
                                                                            'id' => $model->id,
                                                                            #'uncheck' => $model->status ? '0' : null,
                                                                            'uncheck' => null,
                                                                            'checked' => $model->status ? true : false,
                                                                            'value'=>$model->status ? '0' : '1',
                                                                            'template' => '{input}{label}'
                                                                            ]
                                                    )->label(" ", ['class' => ''])}
                                          
                                            </div>
                                            <!-- todo text -->
                                            <span class='text'>
                                            {$form->field($model, 'title')->textInput(
                                                                            [
                                                                            'class' => 'text no-input-style',
                                                                            'id' => $model->id,
                                                                            ]
                                                    )->label(false)}
                                            </span>
                                            <!-- Emphasis label -->
                                            <small class='badge badge-danger'>
                                            <i class='far fa-clock' ></i>
                                            " . Yii::$app->formatter->asRelativeTime($model->created_at) . "</small>
                                            <!-- General tools such as edit or delete-->
                                            <div class='tools'>
                                                <i class='fas fa-edit' data-id=' $model->id'></i>
                                                <i class='fas fa-trash' data-id=' $model->id'></i>
                                            </div>";

                        // or just do some echo
                        // return $model->title . ' posted by ' . $model->author;
                    },
                    'emptyText' => 'Добавьте заметки',
                    'emptyTextOptions' => [
                        'tag' => 'div',
                        'class' => 'col-12 col-lg-6 mb-3 text-info '
                    ],

                ]
            );
            ?>
            <?php ActiveForm::end(); ?>
        </div>

        <?php Pjax::end(); ?>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">

    </div>
    </div>
    </div>
</section>

<?php

$this->registerJs(
    "
		   $('.card').on('click',' input:checkbox', function(e){
                         let form = $('.card').find('#index-todo-form');
                         let id = $(this).attr('id')
                        
                           $.ajax({
                                url: basePath + '/todo/todo/' + 'update?id=' + id,
                                method: form.attr('method'),
                                data: {'Todo[status]': $(this).val()},
                                success: function(data){
                                    $.pjax.reload({container:'#todo-list'});
                                    }
                            });
               });
               
               $('.card').on('click','.fa-trash',function(){
                        let form = $('.card').find('#index-todo-form');
                        
                       let dataId = $(this).attr('data-id')
                            $.ajax({
                                url: basePath + '/todo/todo/' + 'delete?id=' + dataId,
                                method: form.attr('method'),
                                success: function(data){
                                    $.pjax.reload({container:'#todo-list'});
                                    }
                            });
                       });
                       
                $('.card').on('click','.fa-edit',function(e){
                       let attr = $(this).attr('data-id');
                       
                       let input =$('.card').find( 'input[type=text][id='+ attr +'] ');
                       
                       input.removeClass('no-input-style');
                       
                       let id = input.attr('id');
                       let title = input.attr('value');
                       
                    
                       
                                   
                       input.blur( function() {
                         $('input:not(.changed)').attr('disabled', 'disabled');
                           let form = $('.card').find('#index-todo-form');
                           $.ajax({
                                url: basePath + '/todo/todo/' + 'update?id=' + id ,
                                method: form.attr('method'),
                                data:form.serialize(),
                                success: function(data){
                                    $.pjax.reload({container:'#todo-list'});
                                    }
                            });
                            
                            
                       });
                       
                       
                
                });
              
               "
    , yii\web\View::POS_LOAD);
?>




