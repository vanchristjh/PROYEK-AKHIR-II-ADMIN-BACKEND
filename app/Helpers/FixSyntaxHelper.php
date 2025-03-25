<?php

namespace App\Helpers;

class FixSyntaxHelper
{
    /**
     * Helper function to check PHP files for common syntax errors
     * 
     * @param string $directory Directory to check
     * @return array List of files with potential issues
     */
    public static function checkForSyntaxErrors($directory)
    {
        $issues = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                
                // Check for JSON-style syntax in PHP arrays
                if (preg_match('/\[\s*[\'"][a-zA-Z0-9_]+[\'"]:\s*/', $content)) {
                    $issues[] = [
                        'file' => $file->getPathname(),
                        'issue' => 'Possible JSON-style syntax in PHP array (using : instead of =>)'
                    ];
                }
                
                // Check for other common issues
                // Missing commas between array items
                if (preg_match('/=>\s*[^,\]\s\n\r]+\s+[\'"]/', $content)) {
                    $issues[] = [
                        'file' => $file->getPathname(),
                        'issue' => 'Possible missing comma in array'
                    ];
                }
            }
        }
        
        return $issues;
    }
}
