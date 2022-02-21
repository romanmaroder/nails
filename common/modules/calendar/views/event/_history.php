<?php

use yii\grid\GridView;

/* @var $dataHistory EventController */


/*echo '<pre>';
var_dump($dataHistory);
die();*/

foreach ($dataHistory as $value) {
    foreach ($value['event']['master']['rates'] as $rate) {
        if ($value['service_id'] == $rate['service_id']) {
            echo $value['event']['master']['username'] . '&nbsp;';
            echo $value['service']['name'] . '&nbsp;';
            echo Yii::$app->formatter->asDate($value['event']['event_time_start'],'php: M Y') . '&nbsp;';
            echo ($value['amount'] * $rate['rate']) / 100 . "</br><hr>";
        }
    }
}

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
/*echo GridView::widget(
    [
        'dataProvider'     => $dataHistory,
        'showFooter'       => true,
        'tableOptions'     => [
            'class' => 'table table-striped table-bordered',
            'id'    => 'statistic_table'
        ],
        'emptyText'        => 'Ничего не найдено',
        'emptyTextOptions' => [
            'tag'   => 'div',
            'class' => 'col-12 col-lg-6 mb-3 text-info'
        ],
        'columns'          => [
            'id',
            'event.master.username',
            'event.service.name',
            'amount'
            [
                'attribute'     => 'master_id',
                'format'        => 'raw',
                'value'         => function ($model) {
                    return $model->master->username;
                },
                'footerOptions' => ['class' => 'bg-success'],
                'footer'        => Yii::$app->formatter->asCurrency($totalEvent - $totalSalary),
            ],
            [
                'attribute' => 'services.name',
                'format'    => 'raw',
                'value'     => function ($model) {
                    return \common\models\Event::MissingMasterRates($model);
                },
            ],
            [
                'attribute' => 'cost',
                'format'    => 'raw',
                'value'     => function ($model) {
                    $service_one   = '';
                    $service_total = 0;
                    foreach ($model->services as $item) {
                        foreach ($model->master->rates as $master) {
                            if ($master->service_id == $item->id) {
                                $service_one   .= $item->cost . "</br>";
                                $service_total += $item->cost;
                            }
                        }
                    }
                    return $service_one . '<hr>' . Yii::$app->formatter->asCurrency(
                            $service_total
                        );
                },
                'footer'    => Yii::$app->formatter->asCurrency($totalEvent),
            ],
            [
                'attribute' => 'salary',
                'format'    => 'raw',
                'value'     => function ($model) {
                    $salary     = 0;
                    $salary_one = '';
                    $amount     = '';
                    $amount_one = '';
                    foreach ($model->services as $service) {
                        foreach ($model->master->rates as $master) {
                            if ($master->rate < 100 && $master->service_id == $service->id) {
                                $salary_one .= ($service->cost * $master->rate) / 100 . '<br> ';
                                $salary     += ($service->cost * $master->rate) / 100;
                            }
                        }
                    }

                    if ($salary > 0 && $salary_one > 0) {
                        $amount_one = $salary_one;
                        $amount     = '<hr>' . Yii::$app->formatter->asCurrency($salary);
                    }
                    return $amount_one . $amount;
                },
                'footer'    => Yii::$app->formatter->asCurrency($totalSalary),
            ],
            [
                'attribute' => 'client_id',
                'format'    => 'raw',
                'value'     => function ($model) {
                    return $model->client->username;
                },
            ],
            [
                'attribute' => 'event_time_start',
                'format'    => ['date', 'php:d M Y'],
            ],
        ],
    ]
);*/
