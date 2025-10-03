<?php

namespace App\Services\Document;

use App\Models\DocumentTemplate;
use App\Models\DocumentCategory;

class TemplateService
{
    public function getTemplatesByCategory($categoryId)
    {
        $templateModel = new DocumentTemplate();
        return $templateModel->where('category_id', $categoryId)
                            ->where('is_active', 1)
                            ->get();
    }
    
    public function searchTemplates($query, $filters = [])
    {
        $templateModel = new DocumentTemplate();
        $search = $templateModel->where('is_active', 1);
        
        if ($query) {
            $search = $search->where('name', 'LIKE', "%$query%");
        }
        
        if (isset($filters['category_id'])) {
            $search = $search->where('category_id', $filters['category_id']);
        }
        
        if (isset($filters['max_price'])) {
            $search = $search->where('price', '<=', $filters['max_price']);
        }
        
        return $search->orderBy('downloads', 'DESC')->get();
    }
    
    public function getPopularTemplates($limit = 10)
    {
        $templateModel = new DocumentTemplate();
        return $templateModel->where('is_active', 1)
                            ->orderBy('downloads', 'DESC')
                            ->limit($limit)
                            ->get();
    }
    
    public function incrementDownloads($templateId)
    {
        $templateModel = new DocumentTemplate();
        $template = $templateModel->find($templateId);
        
        if ($template) {
            $templateModel->update($templateId, [
                'downloads' => $template->downloads + 1
            ]);
        }
    }
}
