<?php

declare(strict_types=1);

namespace App\Command;

use App\Database;
use App\Model\Place;
use App\Repository\FilenameRepository;
use App\Repository\PlaceRepository;
use App\Util\FileFinder;

abstract class AbstractScanCommand implements CommandInterface
{
    protected FilenameRepository $filenames;
    protected PlaceRepository $places;

    public function __construct(
        protected Database $db,
        protected string $hostname = 'localhost',
    ) {
        $this->filenames = new FilenameRepository($this->db);
        $this->places = new PlaceRepository($this->db);
    }

    public function execute(int $argc, array $argv): void
    {
        if (2 == $argc) {
            $place = $this->places->findById((int) $argv[1]);
            $this->proceedPlace($place);
        } elseif (1 == $argc) {
            $places = $this->places->findByHostname($this->hostname);
            foreach ($places as $place) {
                $this->proceedPlace($place);
            }
        } else {
            $this->showHelp($argv[0]);
        }
    }

    protected function showHelp(string $scriptName): void
    {
        echo 'Usage: ' . $scriptName . ' [placeId]' . PHP_EOL;
    }

    protected function proceedPlace(Place $place): void
    {
        foreach (FileFinder::find($place->path) as $filename) {
            $this->proceedFile($place, $filename);
        }
    }

    abstract protected function proceedFile(Place $place, string $filename): void;
}