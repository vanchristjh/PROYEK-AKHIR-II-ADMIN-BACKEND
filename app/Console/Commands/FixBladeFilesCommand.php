<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixBladeFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blade:fix-syntax';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the "selected\' => \'\'" syntax error in blade files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix blade syntax errors...');

        $bladeFiles = File::glob(resource_path('views') . '/**/*.blade.php');
        $fixedCount = 0;

        foreach ($bladeFiles as $file) {
            $content = File::get($file);
            
            if (strpos($content, "' => '") !== false || 
                strpos($content, "\" => \"") !== false || 
                strpos($content, "' => \"") !== false || 
                strpos($content, "\" => '") !== false) {
                
                // Replace the problematic patterns
                $newContent = preg_replace(
                    ["/'selected' => ''/", "/'checked' => ''/", "/'disabled' => ''/", "/'readonly' => ''/", "/'hidden' => ''/", 
                     "/'required' => ''/", "/'autofocus' => ''/", "/'multiple' => ''/", "/\"selected\" => \"\"/", 
                     "/\"checked\" => \"\"/", "/\"disabled\" => \"\"/", "/\"readonly\" => \"\"/", "/\"hidden\" => \"\"/",
                     "/\"required\" => \"\"/", "/\"autofocus\" => \"\"/", "/\"multiple\" => \"\"/"],
                    ["'selected'", "'checked'", "'disabled'", "'readonly'", "'hidden'", 
                     "'required'", "'autofocus'", "'multiple'", "\"selected\"", 
                     "\"checked\"", "\"disabled\"", "\"readonly\"", "\"hidden\"",
                     "\"required\"", "\"autofocus\"", "\"multiple\""],
                    $content
                );
                
                if ($newContent !== $content) {
                    File::put($file, $newContent);
                    $this->line("Fixed: " . $file);
                    $fixedCount++;
                }
            }
        }

        $this->info("Fixed syntax errors in {$fixedCount} files.");
    }
}
