<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\Card;
use Models\Page;

class PageRepository extends Repository
{
    /**
     * Get all pages
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
            return $page;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function getCards($id)
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT title, text, picture FROM cards WHERE page_id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, Card::class);
            $cards = $stmt->fetchAll();
            return $cards;
        } catch (PDOException $e) {
            echo $e;
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
            return false;
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
            return false;
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
            return false;
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
            return false;
        }
    }
}
