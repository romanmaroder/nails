<?php

use common\modules\calendar\controllers\EventController;
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


$this->title                   = 'Календарь';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">
	<!--    Регистрация переменных для использования в js коде-->

    <?php
    Yii::$app->view->registerJs(
        "app=".Json::encode(Yii::$app->id)."; basePath=".Json::encode(Yii::$app->request->baseUrl).";",
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
    echo '<div id="modalContent"></div>';
    Modal::end(); ?>

    <?php
    Modal::begin(
        [
            'title'   => 'Ошибка',
            'size'    => 'SIZE_SMALL',
            'id'      => 'modal-error',
            'options' => ['tabindex' => '']
        ]
    );
    echo '<div id="modalContent"></div>';
    Modal::end(); ?>


    <?php
    // Модальное окно просмотра и редактирования
    Modal::begin(
        [
            'id'      => 'view',
            'title'   => 'О событии',
            'options' => ['tabindex' => '']
        ]
    );
    Modal::end();
    ?>

    <?= yii2fullcalendar::widget(
        [
            'id' => 'calendar',

            'events'      => [
                'events' => $events,
//                'googleCalendarId' => 'katya04111985@gmail.com',
            ],
            'defaultView' => new JsExpression(
                "
             localStorage.getItem('fcDefaultView') !== null ? localStorage.getItem('fcDefaultView') : 'basicDay'
            "
            ),

//            'googleCalendar'=>true,
            'header'      => [
                'left'   => 'prev,next,today',
                'center' => 'title',
                'right'  => 'month,basicDay,basicWeek,listWeek,agendaDay,agendaWeek'
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
                'buttonText'=>[
                		'listWeek'=>'Повестка недели',
                		'agendaDay'=>'День-Время',
						'agendaWeek'=>'Неделя-Время'
				],
                'views'                => [
                    'month'     => [
                        'eventLimit'       => 10,
                        'displayEventTime' => true, // отображение времени в месяце
                    ],
                    'agendaDay'     => [
                        'displayEventTime' => true, // отображение времени в месяце
                    ],
                    'agendaWeek'     => [
                        'displayEventTime' => true, // отображение времени в месяце
                    ],
                    'day'       => [
                        'eventLimit' => 15,
                    ],
                    'basicWeek' => [
                        'eventLimit'       => false,
                        'displayEventTime' => false
                    ]
                ],
                'eventLimitClick'      => 'popover',
                'theme'                => true,
                'fixedWeekCount'       => false,
                'allDaySlot'=>false,
                //'allDayText'=>false,
                'slotEventOverlap'     => true,
                'agendaEventMinHeight' => 100,
                'slotDuration'         => '0:15:00',
                'slotLabelInterval'    => '01:00:00',
                'slotLabelFormat'      => 'HH:mm',
                'minTime'              => '07:00:00',
                'maxTime'              => '21:00:00',
//                'googleCalendarApiKey' => 'AIzaSyDWfl1aqSSrH19_IxQKWZmOkjorYIvT7vc',
                'defaultDate'          => new JsExpression(
                    "
                localStorage.getItem('fcDefaultViewDate') !==null ? localStorage.getItem('fcDefaultViewDate') : $('#calendar').fullCalendar('getDate')
                "
                ),
                'windowResize'=> new JsExpression("function(view) {
						if( $(window).width() > 540 ){
						 		view.calendar.el.find('.fc-right').find('.btn-group-vertical').removeClass('btn-group-vertical').addClass('btn-group');
						 		view.calendar.el.find('.fc-right').find('.fc-agendaDay-button ').addClass('d-block');
						 		view.calendar.el.find('.fc-right').find('.fc-agendaWeek-button').addClass('d-block');
						}
						if ($(window).width() < 540 ){
								view.calendar.el.find('.fc-right').find('.btn-group').removeClass('btn-group').addClass('btn-group-vertical');
								view.calendar.el.find('.fc-right').find('.fc-agendaDay-button').removeClass('d-block').addClass('d-none');
								view.calendar.el.find('.fc-right').find('.fc-agendaWeek-button').removeClass('d-block').addClass('d-none');
   						}
  				}"
  				),



                'dayClick'             => new JsExpression(
                    "function (date,view) {
                                   if(app == 'app-backend'){
                                        $.ajax({
                                        url:basePath +'/calendar/event/create',
                                        type:'GET',
                                        data:{'date':date.format()},
                                        success:function (data) {			
                                            $('#modal').modal('show').find('#modalContent').html(data)
                                            },
                                        error:function(data,jqXHR, textStatus, errorThrown){
                                        $('#modal').modal('show').find('#modalContent').html(data.error)
                                        }
                                        }
                                        )};
										
                        }"
                ),
                'eventClick'           => new JsExpression(
                    "function(event) {
                    
                     if(app == 'app-backend'){
                        viewUrl = basePath +'/calendar/event/view?id=' + event.id;
                        updateUrl = basePath +'/calendar/event/update?id=' + event.id;
                         $('#edit-link').attr('href', updateUrl);
                     }else{
                        viewUrl = '/calendar/event/view?id=' + event.id;
                        //updateUrl = '/calendar/event/update?id=' + event.id;
                     }
                        
                       
                      $('#view').find('.modal-body').load(viewUrl);
                      $('#view').modal('show');
                    }"
                ),
                'dayRender'            => new JsExpression(
                    "function(cell,date){
console.log(date);
                    } "
                ),
                'eventRender'          => new JsExpression(
                    "function (event, element, view, popover){
							
							if( $(window).width() > 540 ){
						 		view.calendar.el.find('.fc-right').find('.btn-group-vertical').removeClass('btn-group-vertical').addClass('btn-group');
						 		view.calendar.el.find('.fc-right').find('.fc-agendaDay-button ').addClass('d-block');
						 		view.calendar.el.find('.fc-right').find('.fc-agendaWeek-button').addClass('d-block');
							}else if ($(window).width() < 540){
								view.calendar.el.find('.fc-right').find('.btn-group').removeClass('btn-group').addClass('btn-group-vertical');
								view.calendar.el.find('.fc-right').find('.fc-agendaDay-button').removeClass('d-block').addClass('d-none');
								view.calendar.el.find('.fc-right').find('.fc-agendaWeek-button').removeClass('d-block').addClass('d-none');
   							}
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
											content: event.nonstandard.description,
											container:'body'
									}); 
                  				 }
                  				 if ( view.name == 'basicWeek' ){ 
                                     element.popover({
											placement: 'top',
											html: true,
											image: true,
											trigger : 'hover',
											title: event.title + ' ' + event.start.format('HH:mm'),
											content: event.nonstandard.description,
											container:'body'
									}); 
                                 }
                  				 
                  				 
                  				 if ( view.name == 'listWeek' ) { 
                  				  	element.find('.fc-list-item-marker ').append(' (' + event.nonstandard.master_name + ') '); 
                                 }
                                 
                                  if (view.name == 'agendaWeek' || view.name == 'agendaDay' ) {
                                   //element.find('.fc-content').prepend(element.find('.fc-time'));
                                  
									//element.find('.fc-content').addClass('d-flex flex-column');
                                  element.find('.fc-title').addClass('font-weight-bold pb-2').after('<span class=\"fc-description pb-2\"><i>' + event.nonstandard.description + '</i></span>');
                  				 	if( event.nonstandard.notice){
                  				 		element.find('.fc-description').after('<span class=\"fc-notice pb-2\"><i>' + event.nonstandard.notice + '</i></span>');
                  				 	}
                                  }
                  				 
                	}"
                ),
                'viewRender'           => new JsExpression(
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
