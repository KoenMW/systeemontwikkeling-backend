<?php

namespace Controllers;

use Services\EventService;


class EventController extends Controller
{

    function __construct()
    {
        $this->service = new EventService();
    }

    /**
     * gets all events by type
     * @param string $eventType
     * @author Koen Wijchers
     */
    function get($eventType)
    {
        $events = $this->service->getByType($eventType);
        $this->respond($events);
    }
    public function addEvent() {
        $event = $this->createObjectFromPostedJson("Models\\Event");
        $eventId = $this->service->addEvent($event);
        $this->respond(['message' => 'Event added successfully', 'eventId' => $eventId]);
    }
}
