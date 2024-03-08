<?php

namespace Controllers;

use Services\EventService;


class EventController extends Controller
{

    function __construct()
    {
        $this->service = new EventService();
    }

    function get()
    {
        //declare variable test:
        $page = $this->service->getByType("1");
        $this->respond($page);
    }

    function post()
    {
        $this->respond("Hello from TestController");
    }
}
