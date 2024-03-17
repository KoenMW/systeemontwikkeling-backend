<?php

namespace Models;

class checkOrderDTO
{
    public string $id;
    public int $event_id;
    public int $user_id;
    public int $quantity;
    public bool $checkedIn;
    public ?string $comment;
    public ?string $paymentDate;
    public string $title;
    public string $startTime;
    public string $endTime;
    public string $location;
}
