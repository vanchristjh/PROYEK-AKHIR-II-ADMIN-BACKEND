<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\FixMySQLDatabase;
use App\Console\Commands\FixDatabaseConstraints;
use App\Console\Commands\RecreateClassesTable;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        FixMySQLDatabase::class,
        FixDatabaseConstraints::class,
        RecreateClassesTable::class,
    ];

    // ...existing code...
}