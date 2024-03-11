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

    public function getByType($type)
    {
        return $this->repository->getByType($type);
    }
}
