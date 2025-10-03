<?php

namespace App\Services\Search;

use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\Lawyer;

class SearchService
{
    public function search($query, $filters = [])
    {
        $results = [
            'documents' => [],
            'templates' => [],
            'lawyers' => []
        ];
        
        if (empty($query)) {
            return $results;
        }
        
        // Search documents
        if (!isset($filters['type']) || $filters['type'] === 'documents') {
            $results['documents'] = $this->searchDocuments($query, $filters);
        }
        
        // Search templates
        if (!isset($filters['type']) || $filters['type'] === 'templates') {
            $results['templates'] = $this->searchTemplates($query, $filters);
        }
        
        // Search lawyers
        if (!isset($filters['type']) || $filters['type'] === 'lawyers') {
            $results['lawyers'] = $this->searchLawyers($query, $filters);
        }
        
        return $results;
    }
    
    protected function searchDocuments($query, $filters)
    {
        $documentModel = new Document();
        $search = $documentModel->where('title', 'LIKE', "%$query%");
        
        if (isset($filters['status'])) {
            $search = $search->where('status', $filters['status']);
        }
        
        return $search->limit(10)->get();
    }
    
    protected function searchTemplates($query, $filters)
    {
        $templateModel = new DocumentTemplate();
        $search = $templateModel->where('name', 'LIKE', "%$query%")
                                ->where('is_active', 1);
        
        if (isset($filters['category_id'])) {
            $search = $search->where('category_id', $filters['category_id']);
        }
        
        return $search->limit(10)->get();
    }
    
    protected function searchLawyers($query, $filters)
    {
        $lawyerModel = new Lawyer();
        $search = $lawyerModel->where('specialization', 'LIKE', "%$query%")
                             ->where('verification_status', 'verified');
        
        return $search->limit(10)->get();
    }
}
