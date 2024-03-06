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
        $page = $this->service->getOne(1);
        $this->respond($page);
    }

    function post()
    {
        $this->respond("Hello from TestController");
    }
}
