#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Command\MD5ScanCommand;
use App\Database;

require __DIR__ . '/bootstrap.php';
// require 'vendor/autoload.php';

$dbConfig = include __DIR__ . '/config.php';
$db = new Database($dbConfig);

$command = new MD5ScanCommand($db, gethostname());
$command->execute($_SERVER['argc'], $_SERVER['argv']);