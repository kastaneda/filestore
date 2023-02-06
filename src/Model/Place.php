<?php

declare(strict_types=1);

namespace App\Model;

class Place
{
    public int $id;
    public string $hostname;
    public string $path;
    public ?string $comment;
}
