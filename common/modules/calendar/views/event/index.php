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

    <?php Yii::$app->view->registerJs("app=". Json::encode(Yii::$app->id)."; basePath=". Json::encode(Yii::$app->request->baseUrl) .";",  View::POS_HEAD);?>

    <?php
    Modal::begin(
        [
            'title' => 'Добавить событие',
            'size'  => 'SIZE_SMALL',
            'id'    => 'modal',
            'options'=>['tabindex'=>'']
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
            'options'=>['tabindex'=>'']
        ]
    );
    echo '<div id="modalContent"></div>';
    Modal::end(); ?>


    <?php
    // Модальное окно просмотра и редактирования
    Modal::begin(
        [
            'id'    => 'view',
            'title' => 'О событии',
			'options'=>['tabindex'=>'']
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
            'defaultView' => new JsExpression("(localStorage.getItem('fcDefaultView') !== null ? localStorage.getItem('fcDefaultView') :'basicDay') "),
//            'googleCalendar'=>true,
            'header' => [
                'left'   => 'prev,next,today',
                'center' => 'title',
                'right'  => 'month,basicWeek,listWeek'
            ],


            'clientOptions' => [
                'eventOverlap '   => 'red',
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
                                   if(app == 'app-backend'){
                                        $.ajax({
                                        url:basePath +'/calendar/event/create',
                                        type:'GET',
                                        data:{'date':date.format()},
                                        success:function (data) {			
                                            $('#modal').modal('show').find('#modalContent').html(data)
                                            },
                                        error:function(jqXHR, textStatus, errorThrown){}
                                        }
                                        )}
                        }"
                ),
                'eventClick'      => new JsExpression(
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
                'dayRender'       => new JsExpression(
                    "function (date,cell) {} "
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
                    var b = $('#calendar').fullCalendar('getDate');
						localStorage.setItem('fcDefaultView', view.name);
						localStorage.setItem('fcDefaultViewDate', b);
                }"
                )
            ],

        ]
    ); ?>

</div>
