<?php

namespace App\Services\Document;

use App\Models\DocumentTemplate;

class DocumentGeneratorService
{
    public function generateFromTemplate($templateId, $data)
    {
        $templateModel = new DocumentTemplate();
        $template = $templateModel->find($templateId);
        
        if (!$template) {
            return null;
        }
        
        $content = $template->content;
        
        // Replace placeholders with actual data
        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $content = str_replace($placeholder, $value, $content);
        }
        
        return $content;
    }
    
    public function fillTemplate($templateContent, $fields)
    {
        foreach ($fields as $field => $value) {
            $patterns = [
                '{{' . $field . '}}',
                '{' . $field . '}',
                '[' . $field . ']'
            ];
            
            foreach ($patterns as $pattern) {
                $templateContent = str_replace($pattern, $value, $templateContent);
            }
        }
        
        return $templateContent;
    }
    
    public function extractPlaceholders($content)
    {
        preg_match_all('/\{\{([a-zA-Z0-9_]+)\}\}/', $content, $matches);
        return array_unique($matches[1]);
    }
}
