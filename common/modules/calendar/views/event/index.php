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
                'right'  => 'month,basicWeek,listWeek'
            ],


            'clientOptions' => [
                'eventOverlap '     => 'red',
                'todayBtn'          => true,
                'themeSystem'       => 'bootstrap4',
                'navLinks'          => true,
                'contentHeight'     => 'auto',
                'timeFormat'      => 'HH:mm',
                'locale'            => 'ru',
                'eventLimit'        => true,
                'eventOrder'        => '-title',
                'views'             => [
                    'month'     => [
                        'eventLimit'       => 10,
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
                'eventLimitClick'   => 'popover',
                'theme'             => true,
                'fixedWeekCount'    => false,
//                'googleCalendarApiKey' => 'AIzaSyDWfl1aqSSrH19_IxQKWZmOkjorYIvT7vc',
                'defaultDate'       => new JsExpression(
                    "
                localStorage.getItem('fcDefaultViewDate') !==null ? localStorage.getItem('fcDefaultViewDate') : $('#calendar').fullCalendar('getDate')
                "
                ),
                'dayClick'          => new JsExpression(
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
                'eventClick'        => new JsExpression(
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
                'dayRender'         => new JsExpression(
                    "function(){} "
                ),
                'eventRender'       => new JsExpression(
                    "function (event, element, view){
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
                  					element.find('.fc-content').prepend(element.find('.fc-time'))
                  					element.find('.fc-content').find('.fc-time').css({'white-space':'break-spaces'});
                  					element.find('.fc-content').find('.fc-title').addClass('d-none d-sm-block');
                  					
                  				 }
                  				 if ( view.name == 'listWeek' )   
                                      { 
                                     element.find('.fc-list-item-marker ').append(' (' + event.nonstandard.master_name + ') '); 
                                      }
                  				 
                	}"
                ),
                'viewRender'        => new JsExpression(
                    "function (view,event, element){
						localStorage.setItem('fcDefaultView', view.name);
						var date = $('#calendar').fullCalendar('getDate');
							localStorage.setItem('fcDefaultViewDate', date.format());
                }"
                )
            ],

        ]
    ); ?>

</div>
