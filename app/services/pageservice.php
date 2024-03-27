<?php

namespace Services;

use Repositories\PageRepository;
use Repositories\EventRepository;

class PageService
{

    private $repository;
    private $eventRepository;

    function __construct()
    {
        $this->repository = new PageRepository();
        $this->eventRepository = new EventRepository();
    }

    /**
     * gets a page by id
     * @param int $id
     * @return Page
     * @author Koen Wijchers
     */
    public function getPage($id)
    {
        $page = $this->repository->getOne($id);
        $page->events = $this->eventRepository->getEventsByPageId($id);
        return $page;
    }

    /**
     * gets all page names
     * @return array
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function getAllPageNames()
    {
        return $this->repository->getAllPageNames();
    }

    /**
     * gets all links
     * @return array
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function getAllLinks()
    {
        $links = $this->repository->getAllParentPageLinks();
        $links = array_merge($links, $this->repository->getAllChildPageLinks());
        return $links;
    }

    /**
     * gets all parent page links
     * @return array
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function getAllParentPages()
    {
        return $this->repository->getAllParentPages();
    }
}
