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
   /**
    * gets the event by type
    * @param string $type
    * @return Event[]
    * @throws \Exception
    * @author Omar Al Sayasna
    */
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
                eventType,
                page_id,
                detail_page_id
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
                eventType,
                page_id,
                detail_page_id
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
                eventType,
                page_id,
                detail_page_id
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
   /**
    * gets the event by page id
    * @param int $id
    * @throws \Exception
    * @author Omar Al Sayasna
    */
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
            ':page_id' => $event->page_id,
            ':detail_page_id' => $event->detail_page_id,
            ':eventType' => $event->eventType
         ]);


         return $this->connection->lastInsertId();
      } catch (PDOException $e) {
         throw new Exception("Error adding event: " . $e->getMessage());
      }
   }
   /**
    * updates the event
    * @param Event $event
    * @return bool
    * @throws \Exception
    * @author Omar Al Sayasna
    */
   public function updateEvent($event)
   {
      try {
         $stmt = $this->connection->prepare("UPDATE events SET title=:title, startTime=:startTime, endTime=:endTime, price=:price, location=:location, ticket_amount=:ticket_amount, page_id=:page_id, detail_page_id=:detail_page_id, eventType=:eventType WHERE id=:id");
         $stmt->execute([
            ':id' => $event->id,
            ':title' => $event->title,
            ':startTime' => $event->startTime,
            ':endTime' => $event->endTime,
            ':price' => $event->price,
            ':location' => $event->location,
            ':ticket_amount' => $event->ticket_amount,
            ':page_id' => $event->page_id,
            ':detail_page_id' => $event->detail_page_id,
            ':eventType' => $event->eventType,
         ]);

         return $stmt->rowCount() > 0;
      } catch (PDOException $e) {
         throw new Exception("Error updating event: " . $e->getMessage());
      }
   }
   /**
    * deletes the event
    * @param int $id
    * @return bool
    * @throws \Exception
    * @author Omar Al Sayasna
    */
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
   /**
    * gets the event by id
    * @param int $id
    * @return Event
    * @throws \Exception
    * @author Omar Al Sayasna
    */
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
   /**
    * Updates the ticket amount of an event
    * @param int $eventId The id of the event
    * @param int $ticketAmount The new ticket amount
    * @return bool True if the ticket amount was updated successfully, false otherwise
    * @throws Exception If there's an error preparing or executing the SQL statement
    * @author nick
    */
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
