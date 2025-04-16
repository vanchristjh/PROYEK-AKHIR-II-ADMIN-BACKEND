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
    protected $signature = 'app:fix-syntax-errors {--fix : Attempt to automatically fix issues}';

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
        
        $this->warn('Found ' . count($issues) . ' potential issues:');
        
        $filesList = [];
        
        foreach ($issues as $issue) {
            $file = $issue['file'];
            $filesList[] = $file;
            $this->warn($file);
            $this->line('  ' . $issue['issue']);
        }
        
        // Try to fix issues automatically if the --fix flag is provided
        if ($this->option('fix') || $this->confirm('Do you want to attempt to fix these issues automatically?')) {
            $fixedFiles = 0;
            
            foreach ($filesList as $file) {
                if (FixSyntaxHelper::fixColonSyntax($file)) {
                    $this->info("Fixed syntax in: $file");
                    $fixedFiles++;
                }
            }
            
            $this->info("Fixed $fixedFiles files.");
            
            if ($fixedFiles > 0) {
                $this->info('Please run the following commands to clear any cached views:');
                $this->line('  php artisan view:clear');
                $this->line('  php artisan cache:clear');
            }
        } else {
            $this->info('To fix these issues:');
            $this->line('1. Replace any ":" with "=>" in PHP array definitions');
            $this->line('2. Make sure all array items are separated by commas');
            $this->line('3. Ensure all arrays are properly closed with ]');
            $this->line('4. Clear the view cache with: php artisan view:clear');
        }
        
        return Command::SUCCESS;
    }
}
