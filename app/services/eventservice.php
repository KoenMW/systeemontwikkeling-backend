<?php

namespace Services;

use Repositories\EventRepository;
use Models\Event;

class EventService
{

    private $repository;

    function __construct()
    {
        $this->repository = new EventRepository();
    }

    function getAll()
    {
        return $this->repository->getAll();
    }

    /**
     * gets all events by type
     * @param string $type
     * @return array
     * @author Koen Wijchers
     */
    public function getByType($type)
    {
        return $this->repository->getByType($type);
    }
    public function addEvent(Event $event)
    {
        return $this->repository->addEvent($event);
    }
    public function updateEvent($event)
    {
        return $this->repository->updateEvent($event);
    }
    public function deleteEvent($id)
    {
        return $this->repository->deleteEvent($id);
    }
    public function getEventById($id)
    {
        return $this->repository->getEventById($id);
    }
}
