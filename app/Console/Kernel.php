<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\FixMySQLDatabase;
use App\Console\Commands\FixDatabaseConstraints;
use App\Console\Commands\RecreateClassesTable;
use App\Models\Announcement;

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
        \App\Console\Commands\FixAnnouncementsCommand::class,
        \App\Console\Commands\SafeDbReset::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check for upcoming class schedules every minute to send notifications
        $schedule->command('schedules:notify')->everyMinute();

        // Archive expired announcements
        $schedule->call(function () {
            Announcement::where('status', 'published')
                ->where('expired_at', '<', now())
                ->update(['status' => 'archived']);
        })->daily();
        
        // Run the announcement fixer weekly
        $schedule->command('announcements:fix')->weekly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}