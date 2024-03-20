<?php

namespace Models;

class Card
{

    public int $id;
    public string $title;
    public string $text;
    public ?string $picture;
    public ?string $redirect_link;
}
