<?php

use common\modules\todo\controllers\TodoController;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TodoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model TodoController */

$this->title                   = 'Заметки';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
#Регистрация переменных для использования в js коде

Yii::$app->view->registerJs(
    "app=" . Json::encode(Yii::$app->id) . "; basePath=" . Json::encode(Yii::$app->request->baseUrl) . ";",
    View::POS_HEAD
); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Render create form -->
            <?= $this->render(
                '_form',
                [
                    'model'        => $model,
                    'dataProvider' => $dataProvider,
                ]
            ) ?>


            <?php
            Pjax::begin(['id' => 'todo-list', 'options' => ['class' => 'row']]); ?>

            <div class="col-12">

                <?= ListView::widget(
                    [
                        'dataProvider'     => $dataProvider,
                        'options'          => [
                            'tag'         => 'ul',
                            'class'       => 'todo-list ui-sortable col-12',
                            'data-widget' => 'todo-list',
                            'id'          => 'my-todo-list'
                        ],
                        'layout'           => "{items}",
                        'itemOptions'      => ['tag' => 'li'],
                        'itemView'         => function ($model, $key, $index) {
                            return $this->render(
                                '_item_list',
                                [
                                    'model' => $model,
                                    'index' => $index,
                                    'key'   => $key
                                ]
                            );

                            // or just do some echo
                            // return $model->title . ' posted by ' . $model->author;
                        },
                        'emptyText'        => 'Добавьте заметки',
                        'emptyTextOptions' => [
                            'tag'   => 'div',
                            'class' => 'col-12 col-lg-6 mb-3 text-info '
                        ],

                    ]
                );
                ?>
            </div>

            <?php
            Pjax::end(); ?>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">

        </div>
        <div class="overlay dark " id="overlay">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>
    </div>
    </div>
    </div>


</div>






