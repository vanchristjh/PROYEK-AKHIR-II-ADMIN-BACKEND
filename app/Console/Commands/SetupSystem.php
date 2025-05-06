<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the complete system with roles and demo users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up the system...');

        // Run migrations
        $this->info('Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
        $this->info(Artisan::output());

        // Seed the database
        $this->info('Seeding the database...');
        Artisan::call('db:seed', ['--force' => true]);
        $this->info(Artisan::output());

        $this->info('System setup completed successfully!');
        $this->info('You can now log in with these demo accounts:');
        $this->info('Admin: username=admin, password=password');
        $this->info('Guru: username=guru, password=password');
        $this->info('Siswa: username=siswa, password=password');

        return Command::SUCCESS;
    }
}
