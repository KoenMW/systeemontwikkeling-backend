<?php

namespace Models;

class InvoiceDTO
{
    public string $OrderID;
    public int $event_id;
    public int $user_id;
    public int $quantity;
    public ?string $paymentDate;
    public string $EventTitle;
    public string $location;
    public string $username;
    public string $email;
    public int $phoneNumber;
    public string $address;
    public string $startTime;
    public string $endTime;
    public string $price;
    public int $eventType;

}