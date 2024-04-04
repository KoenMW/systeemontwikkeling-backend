<?php

namespace Models;

class Page
{
    public int $id;
    public ?int $parentId;
<<<<<<< HEAD
    public string $name;
=======
    public ?string $name;
>>>>>>> main
    public ?string $intro;
    public ?string $picture;
    public ?array $cards;
    public ?array $infoText;
    public ?array $events;
}
