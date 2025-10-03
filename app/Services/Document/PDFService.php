<?php

namespace App\Services\Document;

use App\Models\Document;

class PDFService
{
    public function generatePDF($documentId)
    {
        $documentModel = new Document();
        $document = $documentModel->find($documentId);
        
        if (!$document) {
            return null;
        }
        
        // In production, use TCPDF or mPDF library
        // For now, returning file path
        
        $fileName = 'document_' . $documentId . '_' . time() . '.pdf';
        $filePath = storage_path('app/documents/' . $fileName);
        
        // Generate PDF content
        $html = $this->generateHTML($document);
        
        // Save PDF (mock implementation)
        // In production: $pdf = new TCPDF(); $pdf->writeHTML($html); $pdf->Output($filePath, 'F');
        
        return $filePath;
    }
    
    protected function generateHTML($document)
    {
        $html = "<!DOCTYPE html><html><head><title>{$document->title}</title></head>";
        $html .= "<body><h1>{$document->title}</h1>";
        $html .= "<div>{$document->content}</div>";
        $html .= "</body></html>";
        
        return $html;
    }
    
    public function downloadPDF($documentId)
    {
        $filePath = $this->generatePDF($documentId);
        
        if (!$filePath || !file_exists($filePath)) {
            return false;
        }
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
        exit;
    }
}
