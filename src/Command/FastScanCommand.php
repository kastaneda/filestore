<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Place;
use App\Model\Filename;

class FastScanCommand extends AbstractFileScanCommand
{
    protected int $countNew = 0;
    protected int $countUpdated = 0;
    protected int $countActual = 0;

    public function execute(int $argc, array $argv): void
    {
        parent::execute($argc, $argv);

        if ($argc <= 2) {
            echo ' * New files: ' . $this->countNew . PHP_EOL;
            echo ' * Updated:   ' . $this->countUpdated . PHP_EOL;
            echo ' * Actual:    ' . $this->countActual . PHP_EOL;
        }
    }

    protected function proceedFile(Place $place, string $filename): void
    {
        $fullName = $place->path . '/' . $filename;
        $model = $orig = $this->filenames->findByFilename($place, $filename);

        if (empty($model)) {
            $model = new Filename;
            $model->placeId = $place->id;
            $model->filename = $filename;
            echo 'New: ' . $fullName . PHP_EOL;
            $this->countNew++;
        }

        $model->bytes = filesize($fullName);
        $model->mtime = filemtime($fullName);

        if ($model != $orig) {
            $model->lastUpdate = time();
            if (!empty($orig)) {
                echo 'Upd: ' . $fullName . PHP_EOL;
                $this->countUpdated++;
            }
            $this->filenames->save($model);
        } else {
            $this->countActual++;
        }
    }
}