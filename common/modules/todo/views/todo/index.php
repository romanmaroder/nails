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
    "app=".Json::encode(Yii::$app->id)."; basePath=".Json::encode(Yii::$app->request->baseUrl).";",
    View::POS_HEAD
); ?>
<section class="content">
    <div class="todo-index">

        <!--<h1><? /*= Html::encode($this->title) */ ?></h1>-->

        <!--<p>
        <? /*= Html::a('Create Todo', ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>-->


        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <!-- Render create form -->
        <?= $this->render(
            '_form',
            [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]
        ) ?>


        <?php Pjax::begin(['id' => 'todo-list', 'options' => ['class' => 'row']]); ?>

        <? /*= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'title',
            'status',
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */ ?>
        <div class="col-12">
            <?php $form = ActiveForm::begin(
                [
                    'options' => ['data-pjax' => true],
                    'id'=>'index-todo-form',
                    'method' => 'post',
                    #'action'=> Url::base().'/todo/todo/',
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
                        'id' => ''
                    ],
                    'layout' => "{items}",
                    'itemOptions' => ['tag' => 'li'],
                    'itemView' => function ($model, $key, $index) use($form)  {
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
                                                                            'uncheck' => null,
                                                                            'checked' => $model->status ? true : false,
                                                                            'template' => '{input}{label}'
                                                                            ]
                                                    )->label(" ", ['class' => ''])}
                                          
                                            </div>
                                            <!-- todo text -->
                                            <span class='text'>{$model->title}</span>
                                            <!-- Emphasis label -->
                                            <small class='badge badge-danger'>
                                            <i class='far fa-clock'></i>
                                            {" . Yii::$app->formatter->asRelativeTime($model->created_at) . "}</small>
                                            <!-- General tools such as edit or delete-->
                                            <div class='tools'>
                                                <i class='fas fa-edit'></i>
                                                <i class='fas fa-trash'></i>
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
</section>
<?php

$this->registerJs(
    '$("document").ready(function(){ 
		/*$("#new_todo").on("pjax:end", function() {
			$.pjax.reload({container:"#todo-list"});  //Reload GridView
		});
		$("#pager").on("pjax:end", function() {
			$.pjax.reload({container:"#todo-list"});  //Reload GridView
		});*/
		/* $("input:checkbox:checked").each(function(){
	            console.log($(this).attr("id"));
        });*/
        
       //console.log($(".card").find("input:checkbox"));
        
       
   
		
    });'
);
?>