<?php

namespace Models;

class Page
{
    public int $id;
    public string $name;
    public string $intro;
    public ?string $picture;
    public ?array $cards;
}
