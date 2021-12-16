<?php

/* @var $modelTodo AccountController */

/* @var $eventsTodoList AccountController */

use yii\bootstrap4\ActiveForm;
use common\modules\profile\controllers\AccountController;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>
<?php
#Регистрация переменных для использования в js коде

Yii::$app->view->registerJs(
    "app=" . Json::encode(Yii::$app->id) . "; basePath=" . Json::encode(Yii::$app->request->baseUrl) . ";",
    View::POS_HEAD
); ?>

    <div class="card">
        <div class="card-header ui-sortable-handle" style="cursor: move;">
            <h3 class="card-title">
                <i class="ion ion-clipboard mr-1"></i>
                Заметки
            </h3>

            <div class="card-tools">


                <?/*=
                ListView::widget(
                    [
                        'dataProvider' => $eventsTodoList,
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
                            'linkContainerOptions' => [

                                'class' => 'page-item'
                            ],
                            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                            'linkOptions' => ['class' => 'page-link'],
                        ],
                    ]
                );
                */?>
            </div>
        </div>
        <!-- /.card-header -->

        <div class="card-body">

<div class="row mb-3">

                <div class="col-9">
                    <?php $form = ActiveForm::begin(
                        [
                            'id' => 'form-todo',
                            'method' => 'post',
                            'action' => "/profile/account/add-todo",
                            'options' => ['data-pjax' => 1,'pjax-container'=>'notes'],
//                            'enableAjaxValidation' => false,
                            'fieldConfig' => [
                                'options' => [
                                    'tag' => false,
                                ]
                            ]
                        ]
                    ) ?>
                    <?= $form->field($modelTodo, 'title')->textInput(
                        [
                            'class' => 'form-control',
                            'maxlength' => true,
                            'placeholder' => 'Добавить заметку',
                        ]
                    )->label(false) ?>
                    <?= $form->field($modelTodo, 'user_id')->hiddenInput(['value' => Yii::$app->user->getId()])->label(
                        false
                    ) ?>
                    <?= $form->field($modelTodo, 'status')->checkbox(
                        ['class' => 'd-none', 'template' => '{input}{label}', 'uncheckValue' => 0]
                    )->label(false) ?>
                </div>

                <div class="col-3 text-right">
                    <?= Html::submitButton(
                        '<i class="fas fa-plus"></i> Добавить',
                        [
                            'class' => 'btn btn-primary'
                        ]
                    ) ?>
                    <?php ActiveForm::end(); ?>

                </div>


</div>


            <div class="row">
                <div class="col-12">
<ul id="todo-list"></ul>
                   <!-- --><?/*=
                    ListView::widget(
                        [
                            'dataProvider' => $eventsTodoList,
                            'options' => [
                                'tag' => 'ul',
                                'class' => 'todo-list ui-sortable col-12',
                                'data-widget' => 'todo-list',
                                'id'=>'notes'
                            ],
                            'layout' => "{items}",
                            'itemOptions' => ['tag' => 'li'],
                            'itemView' => function ($model, $key, $index) use ($form) {
                                return '<!-- drag handle -->
                                            <span class="handle ui-sortable-handle">
                                              <i class="fas fa-ellipsis-v"></i>
                                              <i class="fas fa-ellipsis-v"></i>
                                            </span>
                                            <!-- checkbox -->
                                            <div class="icheck-primary d-inline ml-2">
                                            
                                            ' . $form->field($model, 'status')
                                        ->checkbox(
                                            [
                                                'class' => '',
                                                'id' => $model->id,
                                                'uncheck' => null,
                                                'checked' => $model->status ? true : false,
                                                'template' => '{input}{label}'
                                            ]
                                        )->label(" ", ['class' => '']) . '
                                            </div>
                                            <!-- todo text -->
                                            <span class="text">' . $model->title . ' </span>
                                            <!-- Emphasis label -->
                                            <small class="badge badge-danger"><i class="far fa-clock"></i> '
                                    . Yii::$app->formatter->asRelativeTime($model->created_at) . '</small>
                                            <!-- General tools such as edit or delete-->
                                            <div class="tools">
                                                <i class="fas fa-edit"></i>
                                                <i class="fas fa-trash"></i>
                                            </div>';

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
                    */?>


                </div>
            </div>

        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">


        </div>

    </div>


<?php

$js = <<<JS
 $('#form-todo').on('beforeSubmit', function() {
        // Получаем объект формы
        var testform = $('#form-todo');
      
        // отправляем данные на сервер
        $.ajax({
            // Метод отправки данных (тип запроса)
            type : testform.attr('method'),
            // URL для отправки запроса
           url : basePath + testform.attr('action') ,
            // Данные формы
            data : testform.serialize(),
       
        success:function(data) {
                if (data.error == null) {
                    // Если ответ сервера успешно получен
                     //Сбрасываем значение в поле "title"
                     $("#form-todo")[0].reset();
                     
                     
                     
                     
                     let out =`<li>` + data.data[0].title + `</li>`;
                     
                     $('#todo-list').text(out);
                    //alert(data.data.title)
                } else {
                    // Если при обработке данных на сервере произошла ошибка
                    $("#output").text(data.error)
                }
        },
        error: function(error) {
            // Если произошла ошибка при отправке запроса
            alert(data.data);
            $("#output").text("error3");
        }
        
        
    })
    // Запрещаем прямую отправку данных из формы
        return false;
       
     })
JS;
$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>