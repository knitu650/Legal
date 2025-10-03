<?php

namespace App\Services\Signature;

class ESignatureService
{
    public function generateSignatureFromText($text, $fontFamily = 'cursive')
    {
        // Generate base64 image from text
        $width = 300;
        $height = 100;
        
        $image = imagecreatetruecolor($width, $height);
        
        // Set background to transparent
        $transparent = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $transparent);
        
        // Set text color
        $textColor = imagecolorallocate($image, 0, 0, 0);
        
        // Add text
        imagestring($image, 5, 20, 40, $text, $textColor);
        
        // Convert to base64
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);
        
        return 'data:image/png;base64,' . base64_encode($imageData);
    }
    
    public function validateSignatureImage($imageData)
    {
        // Validate base64 image data
        if (strpos($imageData, 'data:image') !== 0) {
            return false;
        }
        
        // Check size (should be reasonable)
        if (strlen($imageData) > 1000000) { // 1MB
            return false;
        }
        
        return true;
    }
    
    public function addWatermark($signatureData, $watermarkText)
    {
        // Add watermark to signature
        return $signatureData; // Simplified
    }
}
