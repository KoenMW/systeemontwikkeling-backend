<?php

namespace Services;

use Repositories\EventRepository;

class EventService
{

    private $repository;

    function __construct()
    {
        $this->repository = new EventRepository();
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
}
