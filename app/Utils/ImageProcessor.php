<?php

namespace App\Utils;

class ImageProcessor
{
    public function resize($imagePath, $width, $height)
    {
        $imageInfo = getimagesize($imagePath);
        $imageType = $imageInfo[2];
        
        // Create image from file
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($imagePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($imagePath);
                break;
            default:
                return false;
        }
        
        $original_width = imagesx($source);
        $original_height = imagesy($source);
        
        // Create new image
        $dest = imagecreatetruecolor($width, $height);
        
        // Resize
        imagecopyresampled($dest, $source, 0, 0, 0, 0, $width, $height, $original_width, $original_height);
        
        // Save
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($dest, $imagePath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($dest, $imagePath);
                break;
        }
        
        imagedestroy($source);
        imagedestroy($dest);
        
        return true;
    }
    
    public function crop($imagePath, $x, $y, $width, $height)
    {
        // Crop image
        return true;
    }
    
    public function watermark($imagePath, $watermarkText)
    {
        // Add watermark to image
        return true;
    }
    
    public function toBase64($imagePath)
    {
        $imageData = file_get_contents($imagePath);
        $base64 = base64_encode($imageData);
        
        $mimeType = mime_content_type($imagePath);
        
        return "data:$mimeType;base64,$base64";
    }
}
