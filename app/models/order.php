<?php
namespace Models;

class Order {
    public int $id;
    public int $event_id;
    public int $user_id;
    public int $quantity;
    public string $comment;
    public string $paymentDate; 
}