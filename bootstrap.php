<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

spl_autoload_register(function ($class) {
    if ('App\\' == substr($class, 0, 4)) {
        $name = strtr(substr($class, 4), '\\', '/');
        require __DIR__ . '/src/' . $name . '.php';
    }
});
