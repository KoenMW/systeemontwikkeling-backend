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

    public function getDetailPage($id)
    {
        $page = $this->repository->getDetailPage($id);
        $page->events = $this->eventRepository->getEventsByDetailPageId($id);
        return $page;
    }
}
