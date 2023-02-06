<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Model\Place;

class PlaceRepository extends AbstractRepository
{
    protected function getTable(): string
    {
        return 'places';
    }

    protected function getModel(): string
    {
        return Place::class;
    }

    public function findByHostname(string $hostname): array
    {
        return $this->findBy(['hostname' => $hostname]);
    }
}
