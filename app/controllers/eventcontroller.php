<?php

namespace Controllers;

use Services\EventService;


class EventController extends Controller
{

    function __construct()
    {
        $this->service = new EventService();
    }

    function get($eventType)
    {
        //declare variable test:
        $events = $this->service->getByType($eventType);
        $this->respond($events);
    }

    function post()
    {
        $this->respond("Hello from TestController");
    }
}
