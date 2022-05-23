<?php


namespace common\modules\calendar;


use common\models\Event;

class fullCalendar
{
    public Event $events;

    public function __construct(Event $events)
    {
        $this->events = $events;
    }

    public function events(): array
    {
        $data = $this->events->findAllEvents();
        $events=[];
        foreach ($data as $item) {
            $event                  = new \yii2fullcalendar\models\Event();
            $event->id              = $item['id'];
            $event->title           = $item['client']['username'];
            $event->nonstandard     = [
                'description' => $this->events->getServiceName($item['services']) ? $this->events->getServiceName(
                    $item['services']
                ) : $item['description'],
                'notice'      => $item['notice'],
                'master_name' => $item['master']['username'],
            ];
            $event->backgroundColor = $item['master']['profile']['color'];
            $event->start           = $item['event_time_start'];
            $event->end             = $item['event_time_end'];

            $events[] = $event;
        }
        return $events;
    }
}