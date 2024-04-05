<?php

namespace Models;

class Page
{
    public int $id;
    public ?int $parentId;
    public ?string $name;
    public ?string $intro;
    public ?string $picture;
    public ?array $cards;
    public ?array $infoText;
    public ?array $events;
}
