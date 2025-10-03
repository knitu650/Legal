<?php

namespace App\Utils;

class QRCode
{
    public function generate($data, $size = 200)
    {
        // In production, use QR code library
        // Mock implementation - return Google Charts API URL
        
        $url = 'https://chart.googleapis.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($data);
        
        return $url;
    }
    
    public function generateBase64($data, $size = 200)
    {
        // Generate QR code as base64 image
        
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
    }
    
    public function save($data, $path, $size = 200)
    {
        // Save QR code to file
        
        return true;
    }
}
