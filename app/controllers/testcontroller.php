<?php

namespace Controllers;


class TestController extends Controller
{
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