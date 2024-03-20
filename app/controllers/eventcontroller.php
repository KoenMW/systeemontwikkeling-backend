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
     * gets all events by type
     * @param string $eventType
     * @author Koen Wijchers
     */
    function get($eventType)
    {
        $events = $this->service->getByType($eventType);
        $this->respond($events);
    }
    public function addEvent()
    {
        $event = $this->createObjectFromPostedJson("Models\\Event");
        $eventId = $this->service->addEvent($event);
        $this->respond(['message' => 'Event added successfully', 'eventId' => $eventId]);
    }
    public function updateEvent($id)
    {
        try {
            $eventData = $this->createObjectFromPostedJson("Models\\Event");
            $eventData->id = $id;
            $this->service->updateEvent($eventData);
            $this->respond(['message' => 'Event updated successfully.']);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
    public function deleteEvent($id)
{
    try {
        $result = $this->service->deleteEvent($id);
        if ($result) {
            $this->respond(['message' => "Event with ID $id deleted successfully."]);
        } else {
            $this->respondWithError(404, "Event not found or already deleted.");
        }
    } catch (Exception $e) {
        $this->respondWithError(500, "Server error: " . $e->getMessage());
    }
}


}
