<?php

namespace App\Utils;

class PDF
{
    public function generate($html, $filename = 'document.pdf')
    {
        // In production, use TCPDF or mPDF
        // Mock implementation
        
        return $filename;
    }
    
    public function fromView($viewPath, $data, $filename = 'document.pdf')
    {
        // Render view to HTML
        extract($data);
        
        ob_start();
        include $viewPath;
        $html = ob_get_clean();
        
        return $this->generate($html, $filename);
    }
    
    public function download($html, $filename = 'document.pdf')
    {
        $pdfContent = $this->generate($html, $filename);
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo $pdfContent;
        exit;
    }
}
