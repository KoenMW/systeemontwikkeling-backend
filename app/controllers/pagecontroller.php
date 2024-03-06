<?php

namespace Controllers;

use Services\PageService;


class PageController extends Controller
{

    function __construct()
    {
        $this->service = new PageService();
    }

    function get()
    {
        //declare variable test:
        $test = new \stdClass();
        $test->name = "John";
        $test->age = 30;
        $test->city = "New York";
        $this->respond($test);
    }

    function post()
    {
        $this->respond("Hello from TestController");
    }
}
