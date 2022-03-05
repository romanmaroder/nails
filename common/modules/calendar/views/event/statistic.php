<?php


use common\models\EventSearch;
use common\modules\calendar\controllers\EventController;
use yii\bootstrap4\Tabs;

/* @var $dataProvider EventController */
/* @var $searchModel EventSearch */
/* @var $totalEvent EventController */
/* @var $totalSalary EventController */
/* @var $chartEventLabels EventController */
/* @var $chartEventData EventController */

/* @var $dataHistory EventController */
/* @var $totalHistoryAmount EventController */

/* @var $dataProviderExpenseslist EventController */
/* @var $searchModelExpenseslist EventSearch */
/* @var $totalExpenses EventController */
/* @var $chartExpensesLabels EventController */
/* @var $chartExpensesData EventController */

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = $this->title;
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
                                        'searchModel'  => $searchModel,
                                        'totalEvent'   => $totalEvent,
                                        'totalSalary'  => $totalSalary,
                                        'chartEventLabels' => $chartEventLabels,
                                        'chartEventData'   => $chartEventData,
                                    ]
                                ),
                                //'active'  => true, // указывает на активность вкладки
                                'options' => ['id' => 'master-events'],

                            ],
                            [
                                'label'   => 'Расходы',
                                'content' => $this->render(
                                    '_expenseslist',
                                    [
                                        'dataProviderExpenseslist' => $dataProviderExpenseslist,
                                        'searchModelExpenseslist'  => $searchModelExpenseslist,
                                        'totalExpenses'            => $totalExpenses,
                                        'chartExpensesLabels'      => $chartExpensesLabels,
                                        'chartExpensesData'        => $chartExpensesData,
                                    ]
                                ),
                                'options' => ['id' => 'expenseslist'],

                            ],
                            [
                                'label'   => 'История',
                                'content' => $this->render(
                                    '_history',
                                    [
                                        'dataHistory'              => $dataHistory,
                                        'totalHistoryAmount'              => $totalHistoryAmount,
//                                        'searchModelArchive'       => $searchModelArchive,
                                    ]
                                ),
                                'active'  => true, // указывает на активность вкладки
                                'options' => ['id' => 'history'],

                            ],

                        ]
                    ]
                );
                ?>
            </div>

        </div>

    </div>

<?php
//The last tab opened
$tabs = <<<JS
$(function (){
    var storage = localStorage.getItem('nav-tabs');
	if (storage && storage !== '#') {
		$('.nav-tabs a[href="' + storage + '"]').tab('show');
	}

	$('ul.nav li').on('click', function() {
        var id = $(this).find('a').attr('href');
        localStorage.setItem('nav-tabs', id);
    });
})
JS;
$this->registerJs($tabs, $position = yii\web\View::POS_READY, $key = null); ?>