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

            $updatedPage = $this->service->updatePage($page);

            $this->respond($updatedPage);
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
     * Deletes a page by id
     * @param int $id
     * @return void
     * @throws \Exception
     * @author Luko Pecotic
     */
    public function deletePage($id)
    {
        try {
            $this->service->deletePage($id);

            $this->respond(null, 200);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while deleting the page");
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

    /**
     * Creates a new page from posted JSON data
     * @return void
     * @throws \Exception
     * @author Luko Pecotic
     */
    function createPage()
    {
        try {
            if (!$this->checkForJwt(2)) return;

            $page = $this->createObjectFromPostedJson("Models\\Page");

            $createdPage = $this->service->createPage($page);

            $this->respond($createdPage);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while creating the page");
        }
    }

    /**
     * gets all page ids with their names
     * @return array
     * @throws \Exception
     * @author Koen Wijchers
     */
    function getAllPageIdsAndNames()
    {
        try {
            $pages = $this->service->getAllPageIdsAndNames();
            $this->respond($pages);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while retrieving the pages");
        }
    }

    /**
     * gets all detail page ids with their names
     * @return array
     * @throws \Exception
     * @author Koen Wijchers
     */
    function getAllDetailPageIdsAndNames()
    {
        try {
            $pages = $this->service->getAllDetailPageIdsAndNames();
            $this->respond($pages);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while retrieving the pages");
        }
    }
}
