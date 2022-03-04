<?php

use common\modules\calendar\controllers\EventController;
use yii\bootstrap4\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;

/* @var $dataHistory EventController */


/*echo '<pre>';
var_dump($dataHistory->models);
//\common\models\Archive::getHistory();
die();*/

?>
    <div class="row">
        <div class="col-12 col-md-4">

            <?php
            ActiveForm::begin(
                [
                    'id'     => 'search',
                    'method' => 'GET'
                ]
            ); ?>
            <?php
            echo DatePicker::widget(
                [
                    'name'       => 'from_date',
                    //'value'  => $value,
                    //'language' => 'ru',
                    'options'    => [
                        'autocomplete' => 'off'
                    ],
                    'dateFormat' => 'yyyy-MM',
                ]
            ); ?>

            <div class="form-group my-3">
                <?= Html::submitButton('Поиск', ['class' => 'btn btn-sm btn-primary', 'id' => 'btn-search']) ?>
            </div>

            <?php
            ActiveForm::end(); ?>

            <?php
            $form = ActiveForm::begin(
                [
                    'id'     => 'history',
                    'method' => 'post',
                ]
            ); ?>
            <div class="form-group ">
                <?= Html::submitButton(
                    'Отправить',
                    [
                        'class' => 'btn btn-sm btn-primary',
                        'id'    => 'btn-save',
                        'name'  => 'archive',
                        'value' => 'archive'
                    ]
                ) ?>
            </div>
            <?php
            ActiveForm::end(); ?>


        </div>
        <div class="col-12 col-md-8">

                <?php
                /*foreach ($dataHistory->models as $value) {
                    foreach ($value['event']['master']['rates'] as $rate) {
                        if ($value['service_id'] == $rate['service_id']) {
                            echo $value['event']['master']['username'] . '&nbsp;';
                            echo $value['service']['name'] . '&nbsp;';
                            echo Yii::$app->formatter->asDate($value['event']['event_time_start'], 'php: M Y') . '&nbsp;';
                            echo ($value['amount'] * $rate['rate']) / 100 . "</br><hr>";
                        }
                    }
                }*/


                /*echo \yii\widgets\ListView::widget(
                    [
                        'dataProvider' => $dataHistory,
                        'options'      => [
                            'tag'   => 'div',
                            'class' => 'list-wrapper',
                            'id'    => 'list-wrapper',
                        ],
                        'layout'       => "{pager}\n{items}\n{summary}",
                        'itemView'     => function ($model, $key, $index, $widget) {

                        foreach ($model->event->master->rates as $rate){

                            if($model->service_id == $rate->service_id){
                                echo'<pre>';
                                var_dump( $model);

                                die();
                                return $model->event->master->username . '</br>' .
                                    $model->service->name . '</br>' .
                                    $model->event->event_time_start . '</br>' .
                                    $model->event->amount * $rate->rate / 100;
                            }

                        }




                            // or just do some echo
                            // return $model->title . ' posted by ' . $model->author;
                        },
                    ]
                );*/
                echo GridView::widget(
                    [
                        'dataProvider'     => $dataHistory,
                        'showFooter'       => true,
                        'tableOptions'     => [
                            'class' => 'table table-striped table-bordered',
                            'id'    => 'example2'
                        ],
                        'emptyText'        => 'Ничего не найдено',
                        'emptyTextOptions' => [
                            'tag'   => 'div',
                            'class' => 'col-12 col-lg-6 mb-3 text-info'
                        ],
                        'columns'          => [
                            'service.name',
                            'event.master.username',
                            'amount',
                            'event_time_start'
                        ],
                    ]
                );
                 ?>

        </div>
    </div>


<?php

$js = <<< JS
 $(function () {
   $("#example2").DataTable({
      "responsive": true,
      "lengthChange":true,
      //"pageLength": 10,
      "autoWidth": false,
      "info": false,
      'sort':false
    
    })
    
  });
JS;

$this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);

?>