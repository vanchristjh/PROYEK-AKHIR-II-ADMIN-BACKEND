<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImagesHelper
{
    /**
     * Process and save an uploaded image
     *
     * @param UploadedFile $file The uploaded file
     * @param string $path The storage path
     * @param int $maxWidth Maximum width
     * @param int $maxHeight Maximum height
     * @param int $quality JPEG quality (0-100)
     * @return string The relative path to the stored file
     */
    public static function processImage(UploadedFile $file, string $path, int $maxWidth = 800, int $maxHeight = 800, int $quality = 80): string
    {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            // Fall back to regular file upload if GD is not available
            return self::storeFileWithoutProcessing($file, $path);
        }

        try {
            // Get image info
            $imageInfo = getimagesize($file->getPathname());
            if (!$imageInfo) {
                return self::storeFileWithoutProcessing($file, $path);
            }

            $mimeType = $imageInfo['mime'];
            
            // Create image resource based on file type
            $sourceImage = self::createImageFromFile($file->getPathname(), $mimeType);
            if (!$sourceImage) {
                return self::storeFileWithoutProcessing($file, $path);
            }
            
            // Get original dimensions
            $origWidth = imagesx($sourceImage);
            $origHeight = imagesy($sourceImage);
            
            // Calculate new dimensions while maintaining aspect ratio
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $newWidth = (int)($origWidth * $ratio);
            $newHeight = (int)($origHeight * $ratio);
            
            // Create a new image with new dimensions
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG files
            if ($mimeType === 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }
            
            // Resize the image
            imagecopyresampled(
                $newImage, $sourceImage, 
                0, 0, 0, 0, 
                $newWidth, $newHeight, $origWidth, $origHeight
            );
            
            // Generate a unique filename
            $filename = Str::random(20) . '.jpg';
            $fullPath = $path . '/' . $filename;
            
            // Create a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'img');
            
            // Save the image to the temporary file
            imagejpeg($newImage, $tempFile, $quality);
            
            // Store the file using Laravel's Storage
            Storage::disk('public')->put($fullPath, file_get_contents($tempFile));
            
            // Clean up
            imagedestroy($sourceImage);
            imagedestroy($newImage);
            unlink($tempFile);
            
            return $fullPath;
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Image processing failed: ' . $e->getMessage());
            
            // Fall back to regular file upload
            return self::storeFileWithoutProcessing($file, $path);
        }
    }
    
    /**
     * Store a file without any processing
     * 
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    private static function storeFileWithoutProcessing(UploadedFile $file, string $path): string
    {
        $storedPath = $file->store($path, 'public');
        return $storedPath;
    }
    
    /**
     * Create an image resource from a file
     * 
     * @param string $filepath
     * @param string $mimeType
     * @return resource|false
     */
    private static function createImageFromFile(string $filepath, string $mimeType)
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($filepath);
            case 'image/png':
                return imagecreatefrompng($filepath);
            case 'image/gif':
                return imagecreatefromgif($filepath);
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    return imagecreatefromwebp($filepath);
                }
                break;
            case 'image/bmp':
                if (function_exists('imagecreatefrombmp')) {
                    return imagecreatefrombmp($filepath);
                }
                break;
        }
        
        return false;
    }
}
