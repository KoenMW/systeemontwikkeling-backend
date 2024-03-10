<?php

namespace Controllers;

use Services\PageService;


class PageController extends Controller
{

    function __construct()
    {
        $this->service = new PageService();
    }

    function get($page_id)
    {
        $page = $this->service->getOne($page_id);
        $this->respond($page);
    }

    function post()
    {
        $this->respond("Hello from TestController");
    }
}
