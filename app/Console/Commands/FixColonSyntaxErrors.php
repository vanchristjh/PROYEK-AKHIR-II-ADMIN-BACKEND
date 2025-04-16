<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\FixSyntaxHelper;

class FixColonSyntaxErrors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-colon-syntax';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find and fix JavaScript-style object notation (colon) to PHP array syntax (=>)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for JavaScript-style colon syntax in PHP files...');
        
        $issues = FixSyntaxHelper::checkForSyntaxErrors(base_path());
        
        if (empty($issues)) {
            $this->info('No syntax issues found.');
            return Command::SUCCESS;
        }
        
        $this->warn('Found ' . count($issues) . ' potential issues:');
        
        $fixedFiles = 0;
        $filesList = [];
        
        foreach ($issues as $issue) {
            $file = $issue['file'];
            $filesList[] = $file;
            $this->warn($file);
            $this->line('  ' . $issue['issue']);
        }
        
        if ($this->confirm('Do you want to attempt to fix these issues automatically?')) {
            foreach ($filesList as $file) {
                if (FixSyntaxHelper::fixColonSyntax($file)) {
                    $this->info("Fixed syntax in: $file");
                    $fixedFiles++;
                }
            }
            
            $this->info("Fixed $fixedFiles files.");
            
            if ($fixedFiles > 0) {
                $this->info('Please run "php artisan view:clear" and "php artisan cache:clear" to clear any cached views.');
            }
        } else {
            $this->info('To fix these issues manually:');
            $this->line('1. Replace any ":" with "=>" in PHP array definitions');
            $this->line('2. Make sure all array items are separated by commas');
            $this->line('3. Ensure all arrays are properly closed with ]');
            $this->line('4. Clear the view cache with: php artisan view:clear');
        }
        
        return Command::SUCCESS;
    }
}
