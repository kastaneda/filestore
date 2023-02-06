<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Place;

class DummyScanCommand extends AbstractFileScanCommand
{
    protected function proceedFile(Place $place, string $filename): void
    {
        echo $place->path . '/' . $filename . PHP_EOL;
    }
}