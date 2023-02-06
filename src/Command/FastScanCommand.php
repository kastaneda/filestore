<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Place;
use App\Model\Filename;

class FastScanCommand extends AbstractScanCommand
{
    protected function proceedFile(Place $place, string $filename): void
    {
        $fullName = $place->path . '/' . $filename;
        $model = $this->filenames->findByFilename($place, $filename);

        if (empty($model)) {
            $model = new Filename;
            $model->placeId = $place->id;
            $model->filename = $filename;
            echo 'New: ';
        } else {
            echo 'Upd: ';
        }
        echo $fullName . PHP_EOL;

        $model->bytes = filesize($fullName);
        $model->mtime = filemtime($fullName);
        $model->lastUpdate = time();

        $this->filenames->save($model);
    }
}