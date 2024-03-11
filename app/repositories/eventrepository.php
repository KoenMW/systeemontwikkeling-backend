<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\Event;

class EventRepository extends Repository
{
    function getByType($type)
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT id, 
                title, 
                startTime, 
                endTime, 
                price, 
                location, 
                ticket_amount, 
                eventType
                FROM events
                WHERE events.eventType = :type
            ");
            $stmt->bindParam(':type', $type);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, Event::class);
            $page = $stmt->fetchAll();
            return $page;
        } catch (PDOException $e) {
            echo $e;
        }
    }
}
