<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Place;
use App\Util\FileFinder;

abstract class AbstractFileScanCommand extends AbstractScanCommand
{
    protected function proceedPlace(Place $place): void
    {
        foreach (FileFinder::find($place->path) as $filename) {
            $this->proceedFile($place, $filename);
        }
    }

    abstract protected function proceedFile(Place $place, string $filename): void;
}