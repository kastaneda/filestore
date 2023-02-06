<?php

declare(strict_types=1);

namespace App\Model;

class Filename
{
    public int $id;
    public ?int $placeId;
    public string $filename;
    public ?int $bytes;
    public ?int $mtime;
    public ?string $md5;
    public ?int $lastUpdate;
}
