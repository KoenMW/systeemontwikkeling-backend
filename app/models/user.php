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
    public ?string $token;
    public ?\DateTime $reset_token_expire;
    public string $createDate;
    public ?string $img;
    public int $phoneNumber;
    public string $address;
    public $confirmed;
}
