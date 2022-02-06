<?php


use common\models\EventSearch;
use common\modules\calendar\controllers\EventController;
use yii\bootstrap4\Tabs;

/* @var $dataProvider EventController */
/* @var $searchModel EventSearch */
/* @var $dataProviderExpenseslist EventController */
/* @var $searchModelExpenseslist EventSearch */

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
                                //'active'  => true, // указывает на активность вкладки
                                'options' => ['id' => 'master-events'],

                            ],
                            [
                                'label'   => 'Расходы',
                                'content' => $this->render(
                                    '_expenseslist',
                                    [
                                        'dataProviderExpenseslist' => $dataProviderExpenseslist,
                                        'searchModelExpenseslist'  => $searchModelExpenseslist
                                    ]
                                ),
                                //'active' => true, // указывает на активность вкладки
                                'options' => ['id' => 'expenseslist'],

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