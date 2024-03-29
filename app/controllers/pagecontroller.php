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
        try {
            $page = $this->service->getPage($page_id);
            $this->respond($page);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while retrieving the page");
        }
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
        try {
            if (!$this->checkForJwt(2)) return;

            $page = $this->createObjectFromPostedJson("Models\\Page");
            // not jet implemented
            //$this->service->updatePage($page);
            $this->respond($page);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while updating the page");
        }
    }

    /**
     * gets all names of the pages
     * @return array
     * @throws \Exception
     * @author Koen Wijchers
     */
    function getAllPageNames()
    {
        try {
            $pages = $this->service->getAllPageNames();
            $this->respond($pages);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while retrieving the pages");
        }
    }

    /**
     * gets all links of the pages
     * @return array
     * @throws \Exception
     * @author Koen Wijchers
     */
    function getAllLinks()
    {
        try {
            $links = $this->service->getAllLinks();
            $this->respond($links);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while retrieving the links");
        }
    }

    /**
     * gets all parent pages
     * @return array
     * @throws \Exception
     * @author Koen Wijchers
     */
    function getAllParentPages()
    {
        try {
            $pages = $this->service->getAllParentPages();
            $this->respond($pages);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while retrieving the pages");
        }
    }
}
