<?php

namespace Controllers;

use Services\EventService;
use Exception;


class EventController extends Controller
{

    function __construct()
    {
        $this->service = new EventService();
    }
    /**
     * gets all events
     * @throws Exception
     * @author Omar Al Sayasna
     */
    function getAll()
    {
        $events = $this->service->getAll();
        $this->respond($events);
    }

    /**
     * gets all events by type
     * @param string $eventType
     * @author Koen Wijchers
     */
    function get($eventType)
    {
        $events = $this->service->getByType($eventType);
        $this->respond($events);
    }
    /**
     * adds an event
     * @throws Exception
     * @author Omar Al Sayasna
     */
    public function addEvent()
    {
        $event = $this->createObjectFromPostedJson("Models\\Event");
        $eventId = $this->service->addEvent($event);
        $this->respond(['message' => 'Event added successfully', 'eventId' => $eventId]);
    }
    /**
     * updates an event
     * @throws Exception
     * @author Omar Al Sayasna
     */
    public function updateEvent($id)
    {
        try {
            if (!$this->checkForJwt(2)) return;
            $eventData = $this->createObjectFromPostedJson("Models\\Event");
            $eventData->id = $id;
            $this->service->updateEvent($eventData);
            $this->respond(['message' => 'Event updated successfully.']);
        } catch (Exception $e) {
            $this->respondWithError(500, "something went wrong while updating event {$id}");
        }
    }
    /**
     * deletes an event
     * @throws Exception
     * @author Omar Al Sayasna
     */
    public function deleteEvent($id)
    {
        try {
            if (!$this->checkForJwt(2)) return;
            $result = $this->service->deleteEvent($id);
            if ($result) {
                $this->respond(['message' => "Event with ID $id deleted successfully."]);
            } else {
                $this->respondWithError(404, "Event not found or already deleted.");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, "Error while deleting event with ID $id");
        }
    }
    /**
     * gets the event by id
     * @throws Exception
     * @author Omar Al Sayasna
     */
    public function getEventById($id)
    {
        try {
            $event = $this->service->getEventById($id);
            $this->respond($event);
        } catch (Exception $e) {
            $this->respondWithError(500, "Error while getting event with ID $id");
        }
    }
}
