<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;

class PageRepository extends Repository
{
    function getOne($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM 'page' WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS);
            $product = $stmt->fetch();
            // log the output
            print($product);
            return $product;
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
