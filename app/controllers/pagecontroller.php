<?php

namespace Controllers;

use Services\PageService;


class PageController extends Controller
{

    function __construct()
    {
        $this->service = new PageService();
    }

    /**
     * gets a page by id
     * @param int $page_id
     * @author Koen Wijchers
     */
    function get($page_id)
    {
        $page = $this->service->getOne($page_id);
        $this->respond($page);
    }
}
