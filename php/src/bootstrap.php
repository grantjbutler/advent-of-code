<?php

define('ARTISAN_BINARY', 'aoc');

error_reporting(E_ERROR | E_WARNING | E_PARSE);

date_default_timezone_set('America/New_York');

$app = new AOC\Application(
    realpath(__DIR__.'/../')
);

Illuminate\Support\Facades\Facade::clearResolvedInstances();

Illuminate\Support\Facades\Facade::setFacadeApplication($app);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    AOC\Console\Kernel::class
);

return $app;