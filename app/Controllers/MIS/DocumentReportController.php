<?php

namespace App\Controllers\MIS;

use App\Core\Controller;
use App\Models\Document;

class DocumentReportController extends Controller
{
    public function index()
    {
        $documentModel = new Document();
        
        $report = [
            'total_documents' => $documentModel->count(),
            'by_status' => $this->getDocumentsByStatus(),
            'by_category' => $this->getDocumentsByCategory(),
            'creation_trend' => $this->getCreationTrend(),
            'completion_rate' => $this->getCompletionRate(),
            'average_completion_time' => $this->getAverageCompletionTime(),
        ];
        
        $this->view('mis/reports/documents', [
            'pageTitle' => 'Document Report',
            'report' => $report
        ]);
    }
    
    protected function getDocumentsByStatus()
    {
        $documentModel = new Document();
        
        return [
            'draft' => $documentModel->where('status', DOC_STATUS_DRAFT)->count(),
            'pending' => $documentModel->where('status', DOC_STATUS_PENDING)->count(),
            'completed' => $documentModel->where('status', DOC_STATUS_COMPLETED)->count(),
            'signed' => $documentModel->where('status', DOC_STATUS_SIGNED)->count(),
            'archived' => $documentModel->where('status', DOC_STATUS_ARCHIVED)->count(),
        ];
    }
    
    protected function getDocumentsByCategory()
    {
        $documentModel = new Document();
        $documents = $documentModel->get();
        
        $byCategory = [];
        foreach ($documents as $doc) {
            if ($doc->category_id) {
                if (!isset($byCategory[$doc->category_id])) {
                    $byCategory[$doc->category_id] = 0;
                }
                $byCategory[$doc->category_id]++;
            }
        }
        
        return $byCategory;
    }
    
    protected function getCreationTrend()
    {
        $documentModel = new Document();
        $data = ['labels' => [], 'values' => []];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $data['labels'][] = date('M Y', strtotime("-$i months"));
            
            $documents = $documentModel->get();
            $count = 0;
            
            foreach ($documents as $doc) {
                if (date('Y-m', strtotime($doc->created_at)) == $month) {
                    $count++;
                }
            }
            
            $data['values'][] = $count;
        }
        
        return $data;
    }
    
    protected function getCompletionRate()
    {
        $documentModel = new Document();
        $total = $documentModel->count();
        $completed = $documentModel->where('status', DOC_STATUS_COMPLETED)->count();
        $signed = $documentModel->where('status', DOC_STATUS_SIGNED)->count();
        
        if ($total == 0) return 0;
        
        return round((($completed + $signed) / $total) * 100, 2);
    }
    
    protected function getAverageCompletionTime()
    {
        // Calculate average time from creation to completion
        return '2.5 days'; // Placeholder
    }
}
