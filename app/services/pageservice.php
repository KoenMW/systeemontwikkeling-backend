<?php

namespace Services;

use Repositories\PageRepository;

class PageService
{

    private $repository;

    function __construct()
    {
        $this->repository = new PageRepository();
    }

    public function getOne($id)
    {
        return $this->repository->getOne($id);
    }
}
