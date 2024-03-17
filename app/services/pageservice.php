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

    /**
     * gets a page by id
     * @param int $id
     * @return Page
     * @author Koen Wijchers
     */
    public function getOne($id)
    {
        return $this->repository->getOne($id);
    }
}
