<?php


use hail812\adminlte3\assets\PluginAsset;
use yii\bootstrap4\Tabs;

/* @var $dataProvider \common\modules\calendar\controllers\EventController */
/* @var $searchModel \common\models\EventSearch */

?>
<div class="row">
    <div class="col-12">
        <div class="tab-content">
            <?php
            echo Tabs::widget(
                [
                    'options' => ['class' => 'mb-3'],
                    'items'   => [
                        [
                            'label'   => 'Мастера - Услуги',
                            'content' => $this->render(
                                '_event-master-and-service',
                                [
                                    'dataProvider' => $dataProvider,
                                    'searchModel'  => $searchModel
                                ]
                            ),
                            'active'  => true, // указывает на активность вкладки
                            'options' => ['id' => 'events'],

                        ],
                        [
                            'label'   => 'Расходы',
                            'content' => $this->render('_expenseslist'),
                            'options' => ['id' => 'expenseslist'],

                        ],
                    ]
                ]
            );
            ?>
        </div>

    </div>

</div>

