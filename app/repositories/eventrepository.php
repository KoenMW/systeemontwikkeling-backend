<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\Event;
use Exception;

class EventRepository extends Repository
{
   function getAll()
   {
      try {
         $stmt = $this->connection->prepare("SELECT * FROM events");
         $stmt->execute();

         $stmt->setFetchMode(PDO::FETCH_CLASS, Event::class);
         $events = $stmt->fetchAll();
         return $events;
      } catch (PDOException $e) {
         error_log('Error getting events: ' . $e->getMessage());
         throw new Exception('Error getting events');
      }
   }

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
         error_log('Error getting event: ' . $e->getMessage());
         throw new Exception('Error getting event');
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
                WHERE events.page_id = :id OR events.detail_page_id = :id
            ");
         $stmt->bindParam(':id', $id);
         $stmt->execute();

         $stmt->setFetchMode(PDO::FETCH_CLASS, Event::class);
         $events = $stmt->fetchAll();
         return $events;
      } catch (PDOException $e) {
         error_log('Error getting event: ' . $e->getMessage());
         throw new Exception('Error getting event');
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
         error_log('Error getting event: ' . $e->getMessage());
         throw new Exception('Error getting event');
      }
   }
   public function addEvent(Event $event)
   {
      try {
         $stmt = $this->connection->prepare("INSERT INTO events (title, startTime, endTime, price, location, ticket_amount, page_id, detail_page_id, eventType) VALUES (:title, :startTime, :endTime, :price, :location, :ticket_amount, :page_id, :detail_page_id, :eventType)");
         $stmt->execute([
            ':title' => $event->title,
            ':startTime' => $event->startTime,
            ':endTime' => $event->endTime,
            ':price' => $event->price,
            ':location' => $event->location,
            ':ticket_amount' => $event->ticket_amount,
            ':page_id' => $event->page_id ?? null,
            ':detail_page_id' => $event->detail_page_id ?? null,
            ':eventType' => $event->eventType
         ]);
         return $this->connection->lastInsertId();
      } catch (PDOException $e) {
         throw new Exception("Error adding event: " . $e->getMessage());
      }
   }
   public function updateEvent($event)
   {
      try {
         // write event to file:
         file_put_contents('event.txt', json_encode($event) . PHP_EOL, FILE_APPEND);
         $stmt = $this->connection->prepare("UPDATE events SET title=:title, startTime=:startTime, endTime=:endTime, price=:price, location=:location, ticket_amount=:ticket_amount, page_id=:page_id, detail_page_id=:detail_page_id, eventType=:eventType WHERE id=:id");
         $stmt->execute([
            ':id' => $event->id,
            ':title' => $event->title,
            ':startTime' => $event->startTime,
            ':endTime' => $event->endTime,
            ':price' => $event->price,
            ':location' => $event->location,
            ':ticket_amount' => $event->ticket_amount,
            ':page_id' => $event->page_id ?? null,
            ':detail_page_id' => $event->detail_page_id ?? null,
            ':eventType' => $event->eventType,
         ]);

         return $stmt->rowCount() > 0;
      } catch (PDOException $e) {
         throw new Exception("Error updating event: " . $e->getMessage());
      }
   }

   public function deleteEvent($id)
   {
      try {
         $stmt = $this->connection->prepare("DELETE FROM events WHERE id = :id");
         $stmt->bindParam(':id', $id);
         $stmt->execute();

         return $stmt->rowCount() > 0;
      } catch (PDOException $e) {
         throw new Exception("Error deleting event {$id}");
      }
   }
   public function getEventById($id)
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
                WHERE events.id = :id
            ");
         $stmt->bindParam(':id', $id);
         $stmt->execute();

         $stmt->setFetchMode(PDO::FETCH_CLASS, Event::class);
         $event = $stmt->fetch();
         return $event;
      } catch (PDOException $e) {
         error_log('Error getting event: ' . $e->getMessage());
         throw new Exception('Error getting event');
      }
   }
   public function updateEventTicketAmount($eventId, $ticketAmount)
   {
      try {
         $stmt = $this->connection->prepare("UPDATE events SET ticket_amount=:ticket_amount WHERE id=:id");
         $stmt->execute([
            ':id' => $eventId,
            ':ticket_amount' => $ticketAmount,
         ]);

         return $stmt->rowCount() > 0;
      } catch (PDOException $e) {
         throw new Exception("Error updating event ticket amount: " . $e->getMessage());
      }
   }
}
