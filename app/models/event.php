<?php

namespace Models;

class Event
{

    public int $id;
    public string $title;
    public string $startTime;
    public string $endTime;
    public float $price;
    public string $location;
    public int $ticket_amount;
    public int $eventType;
    public $page_id;
    public $detail_page_id;
}
