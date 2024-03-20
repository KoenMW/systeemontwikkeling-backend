<?php

namespace Models;

class User
{

    public int $id;
    public string $password;
    public string $email;
    public int $role;
    public string $create_time;
    public ?string $reset_token;
    public ?\DateTime $reset_token_expires_at;
    public string $createDate;
}