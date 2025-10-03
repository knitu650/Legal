<?php

namespace App\Core\Exception;

class ValidationException extends \Exception
{
    protected $errors = [];
    
    public function __construct($errors = [], $message = 'Validation failed', $code = 422)
    {
        $this->errors = $errors;
        parent::__construct($message, $code);
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}
