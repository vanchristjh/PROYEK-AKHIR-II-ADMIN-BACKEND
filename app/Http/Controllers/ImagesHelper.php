<?php

namespace App\Http\Controllers;

class ImagesHelper
{
    /**
     * Resize and save an image file to a smaller version
     * 
     * @param \Illuminate\Http\UploadedFile $file The uploaded file
     * @param string $directory The storage directory
     * @param int $maxWidth Maximum width
     * @param int $maxHeight Maximum height
     * @param int $quality JPEG quality (1-100)
     * @return string The path to the saved file
     */
    public static function processImage($file, $directory, $maxWidth = 300, $maxHeight = 300, $quality = 80)
    {
        // Create a unique filename
        $filename = 'img_' . time() . '_' . uniqid() . '.jpg';
        $fullPath = storage_path('app/public/' . $directory . '/' . $filename);
        
        // Create directory if it doesn't exist
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        
        // Get image information
        list($width, $height, $type) = getimagesize($file->getPathname());
        
        // Calculate new dimensions
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = $width * $ratio;
        $newHeight = $height * $ratio;
        
        // Create image resource based on file type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $sourceImage = imagecreatefromjpeg($file->getPathname());
                break;
            case IMAGETYPE_PNG:
                $sourceImage = imagecreatefrompng($file->getPathname());
                break;
            case IMAGETYPE_GIF:
                $sourceImage = imagecreatefromgif($file->getPathname());
                break;
            default:
                throw new \Exception('Unsupported image format');
        }
        
        // Create a new image with the calculated dimensions
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG images
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize the image
        imagecopyresampled(
            $newImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight, $width, $height
        );
        
        // Save the image
        imagejpeg($newImage, $fullPath, $quality);
        
        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($newImage);
        
        return $directory . '/' . $filename;
    }
}
