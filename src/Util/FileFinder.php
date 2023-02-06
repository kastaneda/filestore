<?php

declare(strict_types=1);

namespace App\Util;

class FileFinder
{
    public static function find(string $path)
    {
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path)
        );

        $it->rewind();
        while($it->valid()) {        
            if ($it->isFile()) {
                $subPath = $it->getSubPath();
                if ($subPath) {
                    yield $subPath . '/' . $it->getFilename();
                } else {
                    yield $it->getFilename();
                }
            }

            $it->next();
        }
    }
}
