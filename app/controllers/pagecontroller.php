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
        $page = $this->service->getPage($page_id);
        $this->respond($page);
    }

    /**
     * gets a detail page by id
     * @param int $page_id
     * @return Page
     * @throws \Exception
     * @author Koen Wijchers
     */
    function getDetailPage($page_id)
    {
        $page = $this->service->getDetailPage($page_id);
        $this->respond($page);
    }
}
