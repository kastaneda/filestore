<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Filename;
use App\Model\Place;

class MD5ScanCommand extends AbstractScanCommand
{
    protected int $countUpdated = 0;
    protected int $countNotFound = 0;

    public function execute(int $argc, array $argv): void
    {
        parent::execute($argc, $argv);
        if ($argc <= 2) {
            echo ' * Updated:   ' . $this->countUpdated . PHP_EOL;
            echo ' * Not found: ' . $this->countNotFound . PHP_EOL;
        }
    }
    protected function proceedPlace(Place $place): void
    {
        foreach ($this->filenames->findWithoutMD5($place) as $filename) {
            $this->proceedFile($place, $filename);
        }
        echo PHP_EOL;
    }

    protected function proceedFile(Place $place, Filename $filename): void
    {
        $fullName = $place->path . '/' . $filename->filename;
        if (is_file($fullName)) {
            echo '.';
            $filename->md5 = md5_file($fullName);
            $this->filenames->save($filename);
            $this->countUpdated++;
        } else {
            echo '?';
            $this->countNotFound++;
        }
    }
}