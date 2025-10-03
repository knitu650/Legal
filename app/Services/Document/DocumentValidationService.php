<?php

namespace App\Services\Document;

class DocumentValidationService
{
    public function validate($document)
    {
        $errors = [];
        
        // Check required fields
        if (empty($document['title'])) {
            $errors['title'] = 'Title is required';
        }
        
        if (empty($document['content'])) {
            $errors['content'] = 'Content is required';
        }
        
        // Check content length
        if (isset($document['content']) && strlen($document['content']) < 10) {
            $errors['content'] = 'Content must be at least 10 characters';
        }
        
        // Validate template fields are filled
        if (isset($document['template_fields'])) {
            $missing = $this->checkRequiredFields($document['content'], $document['template_fields']);
            if (!empty($missing)) {
                $errors['fields'] = 'Missing required fields: ' . implode(', ', $missing);
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    protected function checkRequiredFields($content, $requiredFields)
    {
        $missing = [];
        
        foreach ($requiredFields as $field) {
            if (strpos($content, '{{' . $field . '}}') !== false) {
                $missing[] = $field;
            }
        }
        
        return $missing;
    }
    
    public function validateSignature($document)
    {
        // Validate document is ready for signature
        if (empty($document->content)) {
            return false;
        }
        
        if ($document->status !== DOC_STATUS_COMPLETED) {
            return false;
        }
        
        return true;
    }
}
