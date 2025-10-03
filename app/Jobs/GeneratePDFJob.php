<?php

namespace App\Jobs;

use App\Utils\PDF;

class GeneratePDFJob
{
    protected $html;
    protected $filename;
    protected $savePath;
    
    public function __construct($html, $filename, $savePath)
    {
        $this->html = $html;
        $this->filename = $filename;
        $this->savePath = $savePath;
    }
    
    public function handle()
    {
        $pdf = new PDF();
        $pdfContent = $pdf->generate($this->html, $this->filename);
        
        // Save to storage
        file_put_contents($this->savePath, $pdfContent);
        
        return $this->savePath;
    }
    
    public function dispatch()
    {
        return $this->handle();
    }
}
