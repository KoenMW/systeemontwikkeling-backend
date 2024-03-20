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
}
