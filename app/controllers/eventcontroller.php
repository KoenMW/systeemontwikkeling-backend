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

}
