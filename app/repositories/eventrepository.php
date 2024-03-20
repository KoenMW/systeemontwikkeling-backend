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

    /**
     * gets the event by page id
     * @param int $id
     * @return Event[]
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function getEventsByPageId(int $id)
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
                WHERE events.page_id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, Event::class);
            $events = $stmt->fetchAll();
            return $events;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    /**
     * gets the event by detail_page id
     * @param int $id
     * @return Event[]
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function getEventsByDetailPageId(int $id)
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
                WHERE events.detail_page_id = :id
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, Event::class);
            $events = $stmt->fetchAll();
            return $events;
        } catch (PDOException $e) {
            echo $e;
        }
    }
    public function addEvent(Event $event) {
        $stmt = $this->connection->prepare("INSERT INTO events (title, startTime, endTime, price, location, ticket_amount, page_id, detail_page_id, eventType) VALUES (:title, :startTime, :endTime, :price, :location, :ticket_amount, :page_id, :detail_page_id, :eventType)");
        $stmt->execute([
            ':title' => $event->title,
            ':startTime' => $event->startTime,
            ':endTime' => $event->endTime,
            ':price' => $event->price,
            ':location' => $event->location,
            ':ticket_amount' => $event->ticket_amount,
            ':page_id' => $event->page_id,
            ':detail_page_id' => $event->detail_page_id,
            ':eventType' => $event->eventType
        ]);
        return $this->connection->lastInsertId();
    }
}
