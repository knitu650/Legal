<?php

namespace App\Controllers\Web;

use App\Core\Controller;
use App\Models\DocumentTemplate;
use App\Models\Document;
use App\Models\Lawyer;

class SearchController extends Controller
{
    public function search()
    {
        $query = $this->request->query('q');
        $type = $this->request->query('type', 'all');
        
        $results = [
            'templates' => [],
            'documents' => [],
            'lawyers' => []
        ];
        
        if ($query) {
            if ($type === 'all' || $type === 'templates') {
                $templateModel = new DocumentTemplate();
                $results['templates'] = $templateModel
                    ->where('name', 'LIKE', "%$query%")
                    ->where('is_active', 1)
                    ->limit(10)
                    ->get();
            }
            
            if ($type === 'all' || $type === 'documents') {
                $documentModel = new Document();
                $results['documents'] = $documentModel
                    ->where('title', 'LIKE', "%$query%")
                    ->where('status', DOC_STATUS_COMPLETED)
                    ->limit(10)
                    ->get();
            }
            
            if ($type === 'all' || $type === 'lawyers') {
                $lawyerModel = new Lawyer();
                $results['lawyers'] = $lawyerModel
                    ->where('specialization', 'LIKE', "%$query%")
                    ->where('verification_status', 'verified')
                    ->limit(10)
                    ->get();
            }
        }
        
        $this->view('web/search/results', [
            'pageTitle' => 'Search Results',
            'query' => $query,
            'results' => $results
        ]);
    }
}
