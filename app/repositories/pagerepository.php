<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\Card;
use Models\Page;

class PageRepository extends Repository
{
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
}
