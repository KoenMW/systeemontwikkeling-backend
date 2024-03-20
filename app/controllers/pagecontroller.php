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

    /**
     * updates a page
     * @param Page $page
     * @return Page
     * @throws \Exception
     * @author Koen Wijchers
     */
    function updatePage()
    {
        $decoded = $this->checkForJwt(2);
        if (!$decoded) {
            return;
        }

        if ($decoded->data->role != 2) {
            $this->respondWithError(401, "Unauthorized");
            return;
        }

        $page = $this->createObjectFromPostedJson("Models\\Page");
        // not jet implemented
        //$this->service->updatePage($page);
        $this->respond($page);
    }
}
