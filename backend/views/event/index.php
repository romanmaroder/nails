<?php

use yii\bootstrap4\Modal;
use yii\web\JsExpression;
use yii2fullcalendar\yii2fullcalendar;

/* @var $this yii\web\View */
/* @var $searchModel common\models\EventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $events \backend\controllers\EventController */

$this->title                   = 'Календарь';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <?php
    Modal::begin(
        [
            'title' => 'Добавить событие',
            'size'  => 'SIZE_SMALL',
            'id'    => 'modal',
        ]
    );
    echo '<div id="modalContent"></div>';
    Modal::end(); ?>

    <?php
    Modal::begin(
        [
            'title' => 'Ошибка',
            'size'  => 'SIZE_SMALL',
            'id'    => 'modal-error',
        ]
    );
    echo '<div id="modalContent"></div>';
    Modal::end(); ?>


    <?php
    // Модальное окно просмотра и редактирования
    Modal::begin(
        [
            'id'    => 'view',
            'title' => 'О событии'
        ]
    );
    Modal::end();
    ?>

    <?= yii2fullcalendar::widget(
        [
            'id'          => 'calendar',

            'events'      => [
                'events'    => $events,
//                'googleCalendarId' => 'katya04111985@gmail.com',
            ],
            'defaultView' => 'basicDay',
//            'googleCalendar'=>true,

            'header' => [
                'left'   => 'prev,next,today',
                'center' => 'title',
                'right'  => 'month,basicWeek,listWeek'
            ],


            'clientOptions' => [

                'todayBtn'        => true,
                'themeSystem'     => 'bootstrap4',
                'navLinks'        => true,
                'contentHeight'   => 'auto',
                'editable'        => true,
                'minTime'         => '07:00',
                'maxTime'         => '19:30',
                'slotDuration'    => '00:15:00',
//                'timezone'        => 'local',
                'locale'          => 'ru',
                'timeFormat'      => 'HH:mm',
                'slotLabelFormat' => 'HH:mm',
                'scrollTime'      => '00:00:30',
                'eventLimit'      => true,
                'eventOrder'      => '-title',
                'views'           => [
                    'month'     => [
                        'eventLimit'       => 10,
                        'displayEventTime' => false,
                    ],
                    'day'       => [
                        'eventLimit' => 15,
                    ],
                    'basicWeek' => [
                        'eventLimit'       => false,
                        'displayEventTime' => false
                    ]
                ],
                'eventLimitClick' => 'popover',
                'theme'           => true,
                'fixedWeekCount'  => false,
//                'googleCalendarApiKey' => 'AIzaSyDWfl1aqSSrH19_IxQKWZmOkjorYIvT7vc',
                'dayClick'        => new JsExpression(
                    "function (date,view) {
                   
                    $.ajax({
                    url:'/admin/event/create',
                    type:'GET',
                    data:{'date':date.format()},
                    success:function (data) {			
						$('#modal').modal('show').find('#modalContent').html(data)
        				},
        			error:function(jqXHR, textStatus, errorThrown){}
                    }
                    )}"
                ),
                'eventClick'      => new JsExpression(
                    "function(event) {
                        viewUrl = '/admin/event/view?id=' + event.id;
                        updateUrl = '/admin/event/update?id=' + event.id;
                        $('#edit-link').attr('href', updateUrl);
                      $('#view').find('.modal-body').load(viewUrl);
                      $('#view').modal('show');
                    }"
                ),
                'dayRender'       => new JsExpression(
                    "function (date,cell) {
                     
				}"
                ),
                'eventRender'     => new JsExpression(
                    "function (event, element, view){
								element.addClass('event-render');
								element.find('.fc-content').append(element.find('.fc-time').addClass('font-italic'));
                  					console.log(event);
                  				if (view.name == 'basicDay' ) { 
                  					element.find('.fc-content').addClass('d-flex flex-column');
                  					element.addClass('fc-basic_day');
                  				 	element.find('.fc-title').addClass('font-weight-bold pb-2').after('<span class=\"fc-description pb-3\"><i>' + event.nonstandard.description + '</i></span>');
                  				 }
                  				 if (view.name == 'month' ) { 
                  					element.addClass('fc-basic_month');
                  					
                  					if(window.width <= 540){
										let string = event.title;
										console.log(
										string.split(/\s/).slice(0,2).reduce((response,word)=> response+=word.slice(0,1),'')
										);
										event.title = string.split(/\s/).slice(0,2).reduce((response,word)=> response+=word.toUpperCase().slice(0,1),'');
										element.find('.fc-title').html(event.title);
									}
                  				 }
                  				 if ( view.name == 'listWeek' )   
                                      { 
                                     element.find('.fc-list-item-marker ').append(' (' + event.nonstandard.master_name + ') '); 
                                      }
                  				 
                	}"
                ),
                /*'viewRender'      => new \yii\web\JsExpression(
                    "function (view, element){
                		if( $('#calendar').fullCalendar('getView') === 'month') {
                				alert('month');
                		}
                }"
                ),*/
                'viewRender'      => new JsExpression(
                    "function (view,event, element){
						var view = $('#calendar').fullCalendar('getView');
//						alert(view.name);
//                		if (view.name != view.name) {
                                    if ( view.name == 'basicWeek' )   
                                      { 
                                          
                                          console.log(\"week\");
                                      }
                                     if (view.name == 'basicDay' ) 
                                      { 
                                         
                                          console.log(\"day\");
                                      }
                                       if (view.name == 'month' ) 
                                      { 
                                       
                                          console.log(\"month\");
                                      }
                                      if (view.name == 'listWeek' ) 
                                      { 
                                         
                                          console.log(\"zzzzzzzzzzzzz\");
                                      }
                                      //You can use it some where else to know what view is active quickly
                                      currentView = view.name;
//                                  }
                }"
                )
            ],
        ]
    ); ?>

</div>
