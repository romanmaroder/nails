<?php

use common\modules\calendar\controllers\EventController;
use hail812\adminlte3\assets\PluginAsset;
use yii\bootstrap4\Modal;
use yii\web\JsExpression;
use yii\web\View;
use yii2fullcalendar\yii2fullcalendar;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $searchModel common\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $events EventController */


Yii::$app->assetManager->bundles['yii\web\JqueryAsset'] = [
    'sourcePath' => null,
    'js'         => ['jquery.js' => 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.js'],
];

PluginAsset::register($this)->add(['sweetalert2']);
$this->title                   = 'Календарь';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <?php
    #Регистрация переменных для использования в js коде

    Yii::$app->view->registerJs(
        "app=" . Json::encode(Yii::$app->id) . "; basePath=" . Json::encode(Yii::$app->request->baseUrl) . ";",
        View::POS_HEAD
    ); ?>

    <?php
    Modal::begin(
        [
            'title'   => 'Добавить событие',
            'size'    => 'SIZE_SMALL',
            'id'      => 'modal',
            'options' => ['tabindex' => '']
        ]
    );
    Modal::end(); ?>

    <?php
    Modal::begin(
        [
            'title'        => 'Ошибка',
            'titleOptions' => [
                'class' => 'text-danger'
            ],
            'size'         => 'SIZE_SMALL',
            'id'           => 'modal-error',
            'options'      => ['tabindex' => '']
        ]
    );
    Modal::end(); ?>


    <?php
    # Модальное окно просмотра и редактирования
    Modal::begin(
        [
            'id'      => 'view',
            'title'   => 'О событии',
            'options' => ['tabindex' => '']
        ]
    );
    Modal::end();
    ?>

    <?php
    if (Yii::$app->session->hasFlash('msg')) {
        $js = "$(function (){
				var Toast = Swal.mixin({
							  toast: true,
							  position: 'top-end',
							  showConfirmButton: false,
							  timer: 5000,
							});
							Toast.fire({
									icon: 'success',
									title: '" . Yii::$app->session->getFlash('msg') . "'
							});	  
				})
		";

        $this->registerJs($js, $position = yii\web\View::POS_READY, $key = null);
    }; ?>


    <?php

    if (Yii::$app->user->can('manager')) {
        $right    = 'month,basicDay,basicWeek,listWeek';
        $editable = true;
    } else {
        $right    = 'month,basicDay,basicWeek,listWeek';
        $editable = false;
    }
    if (Yii::$app->user->can('admin')) {
        $right         = 'month,basicDay,basicWeek,listWeek,agendaDay,agendaWeek';
        $initialView   = 'basicDay';
        $nowIndicator  = true;
        $window_resize = new JsExpression(
            "function(view){
						view.calendar.el.find('.fc-right').find('.btn-group-vertical').removeClass('btn-group-vertical').addClass('btn-group');
						if ($(window).width() < 540 ){
							view.calendar.el.find('.fc-right').find('.btn-group').removeClass('btn-group').addClass('btn-group-vertical');
   						}
        	}"
        );
    }

    /**
     * Triggered when a date/time selection is made
     *
     * @var  $select
     */
    $select = new JsExpression(
        "function (start,end,view) {
							var start = $.fullCalendar.formatDate(start,'Y-MM-DD HH:mm:ss');
							var end = $.fullCalendar.formatDate(end,'Y-MM-DD HH:mm:ss');
                        if(app == 'app-backend'){
                            $.ajax({
								url:basePath +'/calendar/event/create?start='+start+'&end='+end,
								type:'POST',
								//data:{'start':start, 'end':end},
								success:function (data) {
									$('#modal').modal('show').find('.modal-body').html(data);
								},
								error:function(data){
									//$('#modal-error').modal('show').find('.modal-body').html(data.responseText);
									var Toast = Swal.mixin({
															  toast: true,
															  position: 'top-end',
															  showConfirmButton: false,
															  timer: 5000,
															});
															  Toast.fire({
																icon: 'error',
																title: data.responseText
															  });
								},
							});
						}
                    }"
    );


    /**
     * Triggered when resizing stops and the event has changed in duration.
     *
     * @var  $eventResize
     */
    $eventResize = new JsExpression(
        "function(event){
									var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm');
									var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm');
									var id = event.id;
									 if(app == 'app-backend'){
										$.ajax({
											url: basePath +'/calendar/event/update-resize?id='+id+'&start='+start+'&end='+end,
											type: 'POST',
											success: function(data){
											var Toast = Swal.mixin({
															  toast: true,
															  position: 'top-end',
															  showConfirmButton: false,
															  timer: 5000,
															});
															  Toast.fire({
																icon: 'info',
																title: start + ' - ' + end
															  });
												$('#calendar').fullCalendar('refetchEvents');
											},
										});
									 }
						}"
    );

    /**
     * Triggered when dragging stops and the event has moved to a different day/time.
     *
     * @var  $eventDrop
     */
    $eventDrop = new JsExpression(
        "function(event){
									var start = $.fullCalendar.formatDate(event.start, 'Y-MM-DD HH:mm');
									var end = $.fullCalendar.formatDate(event.end, 'Y-MM-DD HH:mm');
									var id = event.id;
									if(app == 'app-backend'){
										$.ajax({
											url: basePath +'/calendar/event/update-drop?id='+id+'&start='+start+'&end='+end,
											type: 'POST',
											success: function(){
											var Toast = Swal.mixin({
															  toast: true,
															  position: 'top-end',
															  showConfirmButton: false,
															  timer: 5000,
															});
															  Toast.fire({
																icon: 'info',
																title: event.title+'</br>'+start + ' - ' + end
															  });
												$('#calendar').fullCalendar('refetchEvents');
											},
										});
									 }
                		}"
    );; ?>

    <?= yii2fullcalendar::widget(
        [
            'id' => 'calendar',

            'events'      => [
                'events' => $events,
            ],
            'defaultView' => new JsExpression(
                "
             localStorage.getItem('fcDefaultView') !== null ? localStorage.getItem('fcDefaultView') : 'basicDay'
            "
            ),

            'header'        => [
                'left'   => 'prev,next,today',
                'center' => 'title',
                'right'  => $right
            ],
            'clientOptions' => [
                'eventOverlap '        => false,
                'todayBtn'             => true,
                'themeSystem'          => 'bootstrap4',
                'navLinks'             => true,
                'contentHeight'        => 'auto',
                'timeFormat'           => 'H:mm',
                'locale'               => 'ru',
                'eventLimit'           => true,
                'eventOrder'           => '-title',
                'buttonText'           => [
                    'listWeek'   => 'Повестка недели',
                    'agendaDay'  => 'День-Время',
                    'agendaWeek' => 'Неделя-Время'
                ],
                'views'                => [
                    'month'      => [
                        'eventLimit'       => 10,
                        'displayEventTime' => true, // отображение времени в месяце
                    ],
                    'agendaDay'  => [
                        'displayEventTime' => true, // отображение времени в месяце
                    ],
                    'agendaWeek' => [
                        'displayEventTime' => true, // отображение времени в месяце
                    ],
                    'day'        => [
                        'eventLimit' => 15,
                    ],
                    'basicWeek'  => [
                        'eventLimit'       => false,
                        'displayEventTime' => false
                    ]
                ],
                'eventLimitClick'      => 'popover',
                'theme'                => true,
                'fixedWeekCount'       => false,
                'allDaySlot'           => false,
                //'allDayText'=>false,
                'slotEventOverlap'     => true,
                'agendaEventMinHeight' => 100,
                'slotDuration'         => '0:15:00',
                'slotLabelInterval'    => '01:00:00',
                'slotLabelFormat'      => 'HH:mm',
                'minTime'              => '07:00:00',
                'maxTime'              => '21:00:00',
                'selectable'           => Yii::$app->user->can('manager'),
                'selectHelper'         => true,
                'select'               => $select,
                'editable'             => $editable,
                'eventResize'          => $eventResize,
                'eventDrop'            => $eventDrop,
                'initialView'          => $initialView,
                'nowIndicator'         => $nowIndicator,
                'defaultDate'          => new JsExpression(
                    "
                localStorage.getItem('fcDefaultViewDate') !==null ? localStorage.getItem('fcDefaultViewDate') : $('#calendar').fullCalendar('getDate')
                "
                ),
                'windowResize'         => $window_resize,

                'eventClick'          => new JsExpression(
                    "function(event) {
                   
                     if(app == 'app-backend'){
                        viewUrl = basePath +'/calendar/event/view?id=' + event.id;
                        updateUrl = basePath +'/calendar/event/update?id=' + event.id;
                         $('#edit-link').attr('href', updateUrl);
                     }else{
                        viewUrl = '/calendar/event/view?id=' + event.id;
                        //updateUrl = '/calendar/event/update?id=' + event.id;
                     }
                        
                      $('.popover').remove();
                      $('#view').find('.modal-body').load(viewUrl);
                      $('#view').modal('show');
                    }"
                ),
                'dayRender'           => new JsExpression(
                    "function(cell,date){
                    } "
                ),
                'eventRender'         => new JsExpression(
                    "function (event, element, view, popover){
								$('.popover').remove();
								element.addClass('event-render');
								element.find('.fc-content').append( element.find('.fc-time').addClass('font-italic') );
								
                  				if (view.name == 'basicDay' ) { 
                  					element.find('.fc-content').addClass('d-flex flex-column');
                  					element.addClass('fc-basic_day');
                  				 	element.find('.fc-title').addClass('font-weight-bold pb-2').after('<span class=\"fc-description pb-2\"><i>' + event.nonstandard.description + '</i></span>');
                  				 	if( event.nonstandard.notice){
                  				 		element.find('.fc-description').after('<span class=\"fc-notice pb-2\"><i>' + event.nonstandard.notice + '</i></span>');
                  				 	}
                  				}
                  				 if (view.name == 'month' ) { 
                  					element.addClass('fc-basic_month');
                  					element.find('.fc-content').prepend(element.find('.fc-time'));
                  					
                  					if(event.title === 'Свободное время'){
                  					    element.find('.fc-title').addClass('free-time');
                  					    element.find('.fc-time').addClass('free-time');
                  					}
                  					
                  					element.find('.fc-content').find('.fc-time').css({'white-space':'break-spaces'});
                  					element.find('.fc-content').find('.fc-title').addClass('d-none d-sm-block').css({'float':'none'});
                  					
                  					if( $('.fc-basic_month').closest('div').length > 0 ){
										element.find('.fc-content').find('.fc-title').removeClass('d-none').addClass('d-inline-block');     
										element.addClass('w-100');
                  					}
                  					
                  					 element.popover({
											placement: 'top',
											html: true,
											image: true,
											trigger : 'hover',
											title: event.title,
											content: event.nonstandard.description ? event.nonstandard.description : '',
											container:'body'
									}); 
                  				 }
                  				 if ( view.name == 'basicWeek' ){ 
                                    let pop =  element.popover({
											placement: 'top',
											html: true,
											image: true,
											trigger : 'hover',
											title: event.title + ' ' + event.start.format('HH:mm'),
											content: event.nonstandard.description ? event.nonstandard.description : '',
											container:'body',
											
									}); 
									if(event.title === 'Свободное время'){
                  					    element.find('.fc-title').addClass('free-time');
                  					}
									
                                 }
                  				 
                  				 
                  				 if ( view.name == 'listWeek' ) { 
                  				  	element.find('.fc-list-item-marker ').append(' (' + event.nonstandard.master_name + ') '); 
                  				  
                                 }
                                 
                                  if (view.name == 'agendaWeek' || view.name == 'agendaDay' ) {
                                  
                                  element.find('.fc-title').addClass('font-weight-bold pb-2').after('<span class=\"fc-description pb-2\"><i>' + event.nonstandard.description + '</i></span>');
                  				 	if( event.nonstandard.notice){
                  				 		element.find('.fc-description').after('<span class=\"fc-notice pb-2\"><i>' + event.nonstandard.notice + '</i></span>');
                  				 	}
                                  }
                  				 
                	}"
                ),
                'eventAfterAllRender' => new JsExpression(
                    "
                	function(view){
						view.calendar.el.find('.fc-right').find('.btn-group-vertical').removeClass('btn-group-vertical').addClass('btn-group');
						if ($(window).width() < 540 ){
							view.calendar.el.find('.fc-right').find('.btn-group').removeClass('btn-group').addClass('btn-group-vertical');
						}
					}
                "
                ),
                'viewRender'          => new JsExpression(
                    "function (view,event, element){
								localStorage.setItem('fcDefaultView', view.name);
								var date = $('#calendar').fullCalendar('getDate');
								localStorage.setItem('fcDefaultViewDate', date.format());
                	}"
                ),
            ],

        ]
    ); ?>

</div>