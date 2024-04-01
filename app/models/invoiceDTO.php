<?php

namespace Models;
Class InvoiceDTO {
    public string $orderId;
    public int $event_id;
    public int $user_id;
    public int $quantity;
    public ?string $paymentDate;
    public string $title;
    public string $location;
    public string $username;
    public string $email;
    public int $phoneNumber;
    public string $address;
    public string $startTime;
    public string $endTime;
    public string $price;
}