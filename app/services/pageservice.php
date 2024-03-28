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
     * deletes a page by id
     * @param int $id
     * @return void
     * @throws \Exception
     * @author Luko Pecotic
     */
    public function deletePage($id)
    {
        $this->repository->deletePage($id);
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

    /**
     * creates a new page
     * @param \Models\Page $page
     * @return \Models\Page
     * @throws \Exception
     */
    public function createPage($page)
    {
        try {
            // Start a transaction
            $this->repository->beginTransaction();

            // Create the page in the database
            $createdPageId = $this->repository->createPage($page->name);

            // Create the cards, infoTexts, and events
            foreach ($page->cards as $card) {
                $this->repository->createCard($card, $createdPageId);
            }

            foreach ($page->infoText as $infoText) {
                $this->repository->createInfoText($infoText, $createdPageId);
            }

            // If the page has a parent, create a detail page
            if ($page->parentId !== null) {
                $this->repository->createDetailPage($createdPageId, $page->parentId);
            }

            // Commit the transaction
            $this->repository->commit();

            // Retrieve the created page
            $createdPage = $this->repository->getOne($createdPageId);

            return $createdPage;
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            $this->repository->rollBack();
            throw new \Exception("An error occurred while creating the page: " . $e->getMessage());
        }
    }
}
