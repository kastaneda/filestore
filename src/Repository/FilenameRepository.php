<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Filename;
use App\Model\Place;

class FilenameRepository extends AbstractRepository
{
    protected function getTable(): string
    {
        return 'filenames';
    }

    protected function getModel(): string
    {
        return Filename::class;
    }

    public function findByFilename(Place $place, string $filename): ?Filename
    {
        return $this->findOneBy(['placeId' => $place->id, 'filename' => $filename]);
    }

    public function save(Filename $filename): int
    {
        if (empty($filename->id)) {
            return $this->insert((array) $filename);
        } else {
            return $this->updateModel((array) $filename);
        }
    }
}
