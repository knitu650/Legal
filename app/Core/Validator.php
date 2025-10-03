<?php

namespace App\Core;

class Validator
{
    protected $errors = [];
    
    public function validate($data, $rules)
    {
        $this->errors = [];
        
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            $value = $data[$field] ?? null;
            
            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $value, $rule, $data);
            }
        }
        
        return empty($this->errors);
    }
    
    protected function applyRule($field, $value, $rule, $data)
    {
        $params = [];
        if (strpos($rule, ':') !== false) {
            list($rule, $paramString) = explode(':', $rule, 2);
            $params = explode(',', $paramString);
        }
        
        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, "The $field field is required.");
                }
                break;
                
            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "The $field must be a valid email address.");
                }
                break;
                
            case 'min':
                if ($value && strlen($value) < $params[0]) {
                    $this->addError($field, "The $field must be at least {$params[0]} characters.");
                }
                break;
                
            case 'max':
                if ($value && strlen($value) > $params[0]) {
                    $this->addError($field, "The $field must not exceed {$params[0]} characters.");
                }
                break;
                
            case 'numeric':
                if ($value && !is_numeric($value)) {
                    $this->addError($field, "The $field must be a number.");
                }
                break;
                
            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if ($value !== ($data[$confirmField] ?? null)) {
                    $this->addError($field, "The $field confirmation does not match.");
                }
                break;
                
            case 'unique':
                // Database check
                list($table, $column) = $params;
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT COUNT(*) as count FROM $table WHERE $column = ?");
                $stmt->execute([$value]);
                if ($stmt->fetch()->count > 0) {
                    $this->addError($field, "The $field has already been taken.");
                }
                break;
        }
    }
    
    protected function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }
    
    public function errors()
    {
        return $this->errors;
    }
    
    public function firstError($field = null)
    {
        if ($field) {
            return $this->errors[$field][0] ?? null;
        }
        
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }
        
        return null;
    }
}
