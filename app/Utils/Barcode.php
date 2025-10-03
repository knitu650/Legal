<?php

namespace App\Utils;

class Barcode
{
    public function generate($data, $type = 'CODE128')
    {
        // In production, use barcode library
        // Mock implementation
        
        return 'data:image/png;base64,barcode_data';
    }
    
    public function save($data, $path, $type = 'CODE128')
    {
        // Save barcode to file
        
        return true;
    }
}
