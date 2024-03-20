<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\Card;
use Models\ChildPage;
use Models\InfoText;
use Models\Page;

class PageRepository extends Repository
{
    /**
     * Gets a page by id
     * @param int $id
     * @author Koen Wijchers
     */
    function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT pages.*, 
                banners.intro, 
                banners.picture
                FROM pages 
                LEFT JOIN banners ON pages.id = banners.page_id
                WHERE pages.id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, Page::class);
            $page = $stmt->fetch();
            $page->cards = $this->getCards($id);
            $page->infoText = $this->getInfoText($id);
            return $page;
        } catch (PDOException $e) {
            error_log('Error getting page: ' . $e->getMessage());
            throw new \Exception('Error getting page');
        }
    }

    /**
     * gets the cards by page id
     * @param int $id 
     * @throws \Exception
     * @author Koen Wijchers 
     */
    private function getCards($id)
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT title, text, picture, redirect_link FROM cards WHERE page_id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, Card::class);
            $cards = $stmt->fetchAll();
            return $cards;
        } catch (PDOException $e) {
            error_log('Error getting cards: ' . $e->getMessage());
            throw new \Exception('Error getting cards');
        }
    }

    /**
     * get the info text by page id
     * @param int $id 
     * @throws \Exception
     * @author Koen Wijchers 
     */
    public function getInfoText($id)
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT title, content, img FROM info_texts WHERE page_id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, InfoText::class);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Error getting info_text: ' . $e->getMessage());
            throw new \Exception('Error getting info_text');
        }
    }

    /**
     * creates a new page
     * @param String $name
     * @return int $id
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function createPage($name)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO pages (name) VALUES (:name)");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            error_log('Error creating page: ' . $e->getMessage());
            throw new \Exception('Error creating page');
        }
    }

    /**
     * creates a new card
     * @param Card $card
     * @param int $page_id
     * @param string $rederectLink
     * @return bool
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function createCard(Card $card, $page_id, $rederectLink = '')
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO cards (title, text, picture, page_id, redirect_link) VALUES (:title, :text, :picture, :page_id, :rederectLink)");
            $stmt->bindParam(':title', $card->title);
            $stmt->bindParam(':text', $card->text);
            $stmt->bindParam(':picture', $card->picture);
            $stmt->bindParam(':page_id', $page_id);
            $stmt->bindParam(':rederectLink', $rederectLink);
            return true;
        } catch (PDOException $e) {
            error_log('Error creating card: ' . $e->getMessage());
            throw new \Exception('Error creating card');
        }
    }

    /**
     * creates a banner
     * @param int $page_id
     * @param string $title
     * @param string $intro
     * @param ?string $picture
     * @return bool
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function createBanner($page_id, $title, $intro, $picture = "")
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO banners (page_id, title, intro, picture) VALUES (:page_id, :title, :intro, :picture)");
            $stmt->bindParam(':page_id', $page_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':intro', $intro);
            $stmt->bindParam(':picture', $picture);
            return true;
        } catch (PDOException $e) {
            error_log('Error creating banner: ' . $e->getMessage());
            throw new \Exception('Error creating banner');
        }
    }

    /**
     * creates a new card
     * @param InfoText $card
     * @param int $page_id
     * @param string $rederectLink
     * @return bool
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function createInfoText(InfoText $infoText, $page_id, $rederectLink = '')
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO info_texts (title, content, img, page_id) VALUES (:title, :content, :img, :page_id)");
            $stmt->bindParam(':title', $infoText->title);
            $stmt->bindParam(':text', $infoText->content);
            $stmt->bindParam(':picture', $infoText->img);
            $stmt->bindParam(':page_id', $page_id);
            return true;
        } catch (PDOException $e) {
            error_log('Error creating info text: ' . $e->getMessage());
            throw new \Exception('Error creating info text');
        }
    }


    /**
     * creates detail_page, parant_id can't be id of a detail_page
     * @param int $page_id
     * @param int $parent_id
     * @return bool
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function createDetailPage($page_id, $parent_id)
    {
        try {
            $stmt = $this->connection->prepare("
                INSERT INTO detail_page (page_id, parent_page_id)
                SELECT :page_id, :parent_id
                WHERE NOT EXISTS (
                    SELECT 1 FROM detail_page WHERE page_id = :parent_id
                )
            ");
            $stmt->bindParam(':page_id', $page_id);
            $stmt->bindParam(':parent_id', $parent_id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log('Error creating detail_page: ' . $e->getMessage());
            throw new \Exception('Error creating detail_page');
        }
    }

    /**
     * gets the detail page by id
     * @param int $id
     * @return Page
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function getDetailPage($id)
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT pages.*, 
                banners.intro, 
                banners.picture,
                parent_pages.name as parentName
                FROM detail_page
                LEFT JOIN pages ON detail_page.page_id = pages.id 
                LEFT JOIN pages as parent_pages ON detail_page.parent_page_id = parent_pages.id
                LEFT JOIN banners ON pages.id = banners.page_id 
                WHERE pages.id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, ChildPage::class);
            $page = $stmt->fetch();
            $page->cards = $this->getCards($id);
            $page->infoText = $this->getInfoText($id);
            return $page;
        } catch (PDOException $e) {
            error_log('Error getting detail page: ' . $e->getMessage());
            throw new \Exception('Error getting detail page');
        }
    }
}
