<?php

namespace Services;

use Repositories\PageRepository;
use Repositories\EventRepository;

use Models\Card;
use Models\InfoText;

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
     * Creates a new page with the given page data
     * @param Models\Page $page
     * @return Models\Page
     * @throws \Exception
     * @author Luko Pecotic
     */
    public function createPage($page)
    {
        try {
            $this->repository->beginTransaction();

            $createdPageId = $this->repository->createPage($page->name);

            $intro = $page->intro ?? null;
            $picture = $page->picture ?? null;

            $this->repository->createBanner($createdPageId, $page->name, $intro, $picture);

            if (isset($page->infoText)) {
                $this->createInfoTexts($page->infoText, $createdPageId);
            }

            if (isset($page->cards)) {
                $this->createCards($page->cards, $createdPageId);
            }
            
            if (isset($page->parentId)) {
                $this->repository->createDetailPage($createdPageId, $page->parentId);
            }

            $this->repository->commit();

            $createdPage = $this->repository->getOne($createdPageId);

            return $createdPage;
        } catch (\Exception $e) {
            $this->repository->rollBack();
            throw new \Exception("An error occurred while creating the page: " . $e->getMessage());
        }
    }

    /**
     * Creates new InfoText instances for a page
     * @param array $infoTextData
     * @param int $createdPageId
     * @return void
     * @throws \Exception
     * @author Luko Pecotic
     */
    private function createInfoTexts($infoTextData, $createdPageId)
    {
        foreach ($infoTextData as $infoTextData) {
            $infoText = new InfoText();
            $infoText->title = $infoTextData->title;
            $infoText->content = $infoTextData->content;
            $infoText->picture = $infoTextData->picture;

            $this->repository->createInfoText($infoText, $createdPageId);
        }
    }

    /**
     * Creates new Card instances for a page
     * @param array $cardData
     * @param int $createdPageId
     * @return void
     * @throws \Exception
     * @author Luko Pecotic
     */
    private function createCards($cardData, $createdPageId)
    {
        foreach ($cardData as $cardData) {
            $card = new Card();
            $card->title = $cardData->title;
            $card->text = $cardData->text;
            $card->picture = $cardData->picture;

            $this->repository->createCard($card, $createdPageId, $cardData->redirect_link);
        }
    }

    /**
     * Updates a page and its related entities
     * @param Models\Page $page
     * @return Models\Page
     * @throws \Exception
     * @author Luko Pecotic
     */
    public function updatePage($page)
    {
        try {
            $this->repository->beginTransaction();

            $this->repository->updatePage($page->id, $page->name);

            $this->deleteInfoTexts($page);
            $this->deleteCards($page);

            $intro = $page->intro ?? null;
            $picture = $page->picture ?? null;

            $this->repository->updateBanner($page->id, $page->name, $page->intro, $page->picture);

            if (isset($page->infoText)) {
                $this->updateInfoTexts($page->infoText, $page->id);
            }

            if (isset($page->cards)) {
                $this->updateCards($page->cards, $page->id);
            }

            if (isset($page->parentId)) {
                $this->repository->updateDetailPage($page->id, $page->parentId);
            }

            $this->repository->commit();

            $updatedPage = $this->repository->getOne($page->id);

            return $updatedPage;
        } catch (\Exception $e) {
            $this->repository->rollBack();
            throw new \Exception("An error occurred while updating the page: " . $e->getMessage());
        }
    }

    /**
     * Deletes InfoText instances from a page that are not in the updated page data
     * @param Models\Page $page
     * @return void
     * @throws \Exception
     * @author Luko Pecotic
     */
    private function deleteInfoTexts($page)
    {
        $currentInfoTextIds = $this->repository->getInfoTextIdsByPageId($page->id);
        $infoTextIds = isset($page->infoTexts) ? array_map(function($infoText) { return $infoText->id; }, $page->infoTexts) : [];

        foreach ($currentInfoTextIds as $currentInfoTextId) {
            if (!in_array($currentInfoTextId, $infoTextIds)) {
                $this->repository->deleteInfoText($currentInfoTextId);
            }
        }
    }

    /**
     * Deletes Card instances from a page that are not in the updated page data
     * @param Models\Page $page
     * @return void
     * @throws \Exception
     * @author Luko Pecotic
     */
    private function deleteCards($page)
    {
        $currentCardIds = $this->repository->getCardIdsByPageId($page->id);
        $cardIds = isset($page->cards) ? array_map(function($card) { return $card->id; }, $page->cards) : [];
    
        foreach ($currentCardIds as $currentCardId) {
            if (!in_array($currentCardId, $cardIds)) {
                $this->repository->deleteCard($currentCardId);
            }
        }
    }

    /**
     * Updates existing InfoText instances or creates new ones for a page
     * @param array $infoTextsData
     * @param int $pageId
     * @return void
     * @throws \Exception
     * @author Luko Pecotic
     */
    private function updateInfoTexts($infoTextsData, $pageId)
    {
        foreach ($infoTextsData as $infoTextData) {
            $infoText = new InfoText();
            $infoText->id = $infoTextData->id;
            $infoText->title = $infoTextData->title;
            $infoText->content = $infoTextData->content;
            $infoText->picture = $infoTextData->picture;
            
            if ($this->repository->infoTextExists($infoText->id)) {
                $this->repository->updateInfoText($infoText, $pageId);
            } else {
                $this->repository->createInfoText($infoText, $pageId);
            }
        }
    }

    /**
     * Updates existing Card instances or creates new ones for a page
     * @param array $cardsData
     * @param int $pageId
     * @return void
     * @throws \Exception
     * @author Luko Pecotic
     */
    private function updateCards($cardsData, $pageId)
    {
        foreach ($cardsData as $cardData) {
            $card = new Card();
            $card->id = $cardData->id;
            $card->title = $cardData->title;
            $card->text = $cardData->text;
            $card->picture = $cardData->picture;
            $card->redirect_link = $cardData->redirect_link;

            if ($this->repository->cardExists($card->id)) {
                $this->repository->updateCard($card, $pageId);
            } else {
                $this->repository->createCard($card, $pageId, $cardData->redirect_link);
            }
        }
    }
}
