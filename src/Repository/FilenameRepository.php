<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Filename;

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
}
