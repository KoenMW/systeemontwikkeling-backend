<?php

namespace Models;

class User
{

    public int $id;
    public string $username;
    public string $password;
    public string $email;
    public int $role;
    public string $create_time;
    public ?string $reset_token;
    public ?\DateTime $reset_token_expires_at;
    public string $createDate;
    public string $img;
    public int $phoneNumber;
    public string $address;
    public $confirmed;
}