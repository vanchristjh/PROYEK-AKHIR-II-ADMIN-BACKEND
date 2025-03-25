<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\FixSyntaxHelper;

class FixSyntaxErrors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-syntax-errors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find and fix common PHP syntax errors in the project';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for syntax errors...');
        
        $issues = FixSyntaxHelper::checkForSyntaxErrors(base_path());
        
        if (empty($issues)) {
            $this->info('No common syntax issues found.');
            $this->info('The error might be in a compiled view file. Try running:');
            $this->line('  php artisan view:clear');
            $this->line('  php artisan cache:clear');
            return Command::SUCCESS;
        }
        
        $this->info('Found ' . count($issues) . ' potential issues:');
        
        foreach ($issues as $issue) {
            $this->warn($issue['file']);
            $this->line('  ' . $issue['issue']);
        }
        
        $this->info('To fix these issues:');
        $this->line('1. Replace any ":" with "=>" in PHP array definitions');
        $this->line('2. Make sure all array items are separated by commas');
        $this->line('3. Ensure all arrays are properly closed with ]');
        $this->line('4. Clear the view cache with: php artisan view:clear');
        
        return Command::SUCCESS;
    }
}
