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

    public function findWithoutMD5(Place $place): array
    {
        $sql = sprintf(
            'SELECT * FROM `%s` WHERE `placeId` = :placeId AND `md5` IS NULL',
            $this->getTable(),
        );

        $param = ['placeId' => $place->id];
        return $this->db->fetch($this->getModel(), $sql, $param);
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
