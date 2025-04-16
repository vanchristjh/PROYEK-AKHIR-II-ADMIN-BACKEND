<?php

namespace App\Helpers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class FixSyntaxHelper
{
    /**
     * Check for common syntax errors in PHP files
     *
     * @param string $directory Directory to scan
     * @return array Array of issues found
     */
    public static function checkForSyntaxErrors($directory)
    {
        $issues = [];
        
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        $phpFiles = new RegexIterator($iterator, '/\.php$/i');
        
        foreach ($phpFiles as $file) {
            if (strpos($file->getPathname(), 'vendor/') !== false) {
                continue; // Skip vendor directory
            }
            
            $content = file_get_contents($file->getPathname());
            
            // Check for JavaScript style object notation (key: value) instead of PHP array syntax (key => value)
            if (preg_match('/[\'"][a-zA-Z0-9_]+[\'"]\s*:(?!\:)\s*[^\s=]/', $content)) {
                $issues[] = [
                    'file' => $file->getPathname(),
                    'issue' => 'Found JavaScript-style object notation (key: value) instead of PHP array syntax (key => value)'
                ];
            }
            
            // Check for missing closing brackets/parentheses
            $openBrackets = substr_count($content, '{');
            $closeBrackets = substr_count($content, '}');
            if ($openBrackets != $closeBrackets) {
                $issues[] = [
                    'file' => $file->getPathname(),
                    'issue' => "Mismatched curly braces: $openBrackets open vs $closeBrackets closed"
                ];
            }
            
            // Check for mismatched array brackets
            $openArrayBrackets = substr_count($content, '[');
            $closeArrayBrackets = substr_count($content, ']');
            if ($openArrayBrackets != $closeArrayBrackets) {
                $issues[] = [
                    'file' => $file->getPathname(),
                    'issue' => "Mismatched array brackets: $openArrayBrackets open vs $closeArrayBrackets closed"
                ];
            }
            
            // Check for mismatched parentheses
            $openParens = substr_count($content, '(');
            $closeParens = substr_count($content, ')');
            if ($openParens != $closeParens) {
                $issues[] = [
                    'file' => $file->getPathname(),
                    'issue' => "Mismatched parentheses: $openParens open vs $closeParens closed"
                ];
            }
            
            // Missing commas between array items
            if (preg_match('/=>\s*[^,\]\s\n\r]+\s+[\'"]/', $content)) {
                $issues[] = [
                    'file' => $file->getPathname(),
                    'issue' => 'Possible missing comma in array'
                ];
            }
        }
        
        return $issues;
    }

    /**
     * Fix the JavaScript-style object notation to PHP array syntax
     *
     * @param string $filePath Path to the file to fix
     * @return bool True if fixes were applied
     */
    public static function fixColonSyntax($filePath)
    {
        if (!file_exists($filePath)) {
            return false;
        }
        
        $content = file_get_contents($filePath);
        $pattern = '/([\'"][a-zA-Z0-9_]+[\'"])\s*:(?!\:)\s*/';
        $replacement = '$1 => ';
        
        $newContent = preg_replace($pattern, $replacement, $content);
        
        if ($newContent !== $content) {
            file_put_contents($filePath, $newContent);
            return true;
        }
        
        return false;
    }
}
