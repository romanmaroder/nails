<?php


use common\models\EventSearch;
use common\modules\calendar\controllers\EventController;
use hail812\adminlte3\assets\PluginAsset;
use yii\bootstrap4\Tabs;
use yii\widgets\Pjax;

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
/* @var $chartExpensesLabels EventController */
/* @var $chartExpensesData EventController */


$this->title = 'Статистика';
$this->params['breadcrumbs'][] = $this->title;
PluginAsset::register($this)->add(['datatables', 'datatables-bs4', 'datatables-responsive', 'datatables-buttons']);

?>

    <div class="row">
        <div class="col-12">
            <div class="tab-content">
                <?php Pjax::begin() ?>
                <?php echo Tabs::widget(
                    [
                        'options' => ['class' => 'mb-3'],
                        'items'   => [
                            [
                                'label'       => 'Мастера - Услуги',
                                'content'     => $this->render(
                                    '_event-master-and-service',
                                    [
                                        'dataProvider'     => $dataProvider,
                                        'searchModel'      => $searchModel,
                                        'totalEvent'       => $totalEvent,
                                        'totalSalary'      => $totalSalary,
                                        'chartEventLabels' => $chartEventLabels,
                                        'chartEventData'   => $chartEventData,
                                    ]
                                ),
                                //'active'  => true, // указывает на активность вкладки
                                'options'     => ['id' => 'master-events'],
                                'linkOptions' => ['data-id' => 'master-events'],

                            ],
                            [
                                'label'       => 'Расходы',
                                'content'     => $this->render(
                                    '_expenseslist',
                                    [
                                        'dataProviderExpenseslist' => $dataProviderExpenseslist,
                                        'searchModelExpenseslist'  => $searchModelExpenseslist,
                                        'chartExpensesLabels'      => $chartExpensesLabels,
                                        'chartExpensesData'        => $chartExpensesData,
                                    ]
                                ),
                                //'active'  => true, // указывает на активность вкладки
                                'options'     => ['id' => 'expenseslist'],
                                'linkOptions' => ['data-id' => 'expenseslist'],

                            ],
                            [
                                'label'       => 'История',
                                'content'     => $this->render(
                                    '_history',
                                    [
                                        'dataHistory' => $dataHistory,
                                    ]
                                ),
                                //'active'  => true, // указывает на активность вкладки
                                'options'     => ['id' => 'history'],
                                'linkOptions' => ['data-id' => 'history'],

                            ],

                        ]
                    ]
                ); ?>
                <?php Pjax::end() ?>
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
	  $(document).on('beforeSubmit', '#search, #history', function(event) {
    $(this).find('[type=submit]').attr('disabled', true).addClass('disabled');
    });
 
})
JS;

// Initializing the DataTable plugin on the selected tab
$initTable = <<<JS
$(function (){
    
	function initTable(currentTab = $('table')){
	    $(currentTab).DataTable({
                "bDestroy": true,
                "responsive": true,
                "pageLength": 10,
                "paging": true,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "bStateSave": true,
                "dom": "<'row'<'col-12 col-sm-6 d-flex align-content-md-start'f><'col-12 col-sm-6 d-flex justify-content-sm-end'l>>tp",
                "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api();
                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };
                            // Total over all pages
                            totalAmount = api
                                .column( 2)
                                .nodes()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal($(b).attr('data-total'));
                                }, 0 );
                            // Total over this page
                            pageTotalAmount = api
                                .column( 2, { page: 'current'} )
                                .nodes()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal($(b).attr('data-total'));
                                }, 0 );
                            // Update footer
                            $( api.column( 2 ).footer() )
                            //.html( pageTotalAmount.toLocaleString('ru') + ' &#8381;'+' <hr> '+ totalAmount .toLocaleString('ru') + ' &#8381;');
                            .html( pageTotalAmount.toLocaleString('ru') + ' &#8381;');
                            // Total over all pages
                            totalSalary = api
                                .column( 3 )
                                .nodes()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal($(b).attr('data-total'));
                                }, 0 );
                            // Total over this page
                            pageTotalSalary = api
                                .column( 3, { page: 'current'} )
                                .nodes()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal($(b).attr('data-total'));
                                }, 0 );
                            // Update footer
                            if ( pageTotalSalary == 0 ){
                                 $( api.column( 3 ).footer() )
                                 //.html(pageTotalSalary.toLocaleString('ru') + ' &#8381;'+' <hr> '+ totalSalary.toLocaleString('ru') + ' &#8381;');
                                 .html('-').css({'text-align':'center'});
                            }else{
                                 $( api.column( 3 ).footer() ).html(pageTotalSalary.toLocaleString('ru') + ' &#8381;').css({'text-align':'center'});
                            }
                            
                            //
                            
                             var diffPage =  pageTotalAmount - pageTotalSalary;
                             var diffTotal =  totalAmount - totalSalary;
                            $( api.column( 0 ).footer() )
                            //.html( diffPage.toLocaleString('ru') + ' &#8381;'+' <hr>'+ diffTotal.toLocaleString('ru') + ' &#8381;');
                            .html( diffPage.toLocaleString('ru') + ' &#8381;');
                            
                            
                        },
                "fnStateSave": function (oSettings, oData) {
                localStorage.setItem('DataTables_' + window.location.pathname, JSON.stringify(oData));
                },
                "fnStateLoad": function () {
                var data = localStorage.getItem('DataTables_' + window.location.pathname);
                return JSON.parse(data);
                },
                "language": {
                "lengthMenu": 'Показать <select class="form-control form-control-sm">'+
                    '<option value="10">10</option>'+
                    '<option value="20">20</option>'+
                    '<option value="50">50</option>'+
                    '<option value="-1">Все</option>'+
                    '</select>',
                "search": "Поиск:",
                "zeroRecords": "Совпадений не найдено",
                "emptyTable": "В таблице отсутствуют данные",
                "paginate": {
                "first": "Первая",
                "previous": '<i class="fas fa-backward"></i>',
                "last": "Последняя",
                "next": '<i class="fas fa-forward"></i>'
                }
                },
    }).buttons().container().appendTo('#statistic_table_wrapper .col-md-6:eq(0)');
	}
	
	// DataTable reinitialization after pjax execution
	 $(document).on('pjax:complete', function() {
        initTable();
    });
	
	 if ( $.fn.dataTable.isDataTable(  initTable() ) ) {
	     $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
              var currentTab = $(e.target).attr('data-id');
              switch (currentTab)   {
                 case currentTab  :   //do nothing
                      initTable('#' +currentTab);
                    break ;
                 default: //do nothing 
              };
        });
	 }
})
JS;


$this->registerJs($tabs, $position = yii\web\View::POS_READY, $key = null);
$this->registerJs($initTable, $position = yii\web\View::POS_READY, $key = null); ?>