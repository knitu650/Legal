<?php

namespace App\Core\Exception;

class DatabaseException extends \Exception
{
    protected $message = 'Database error occurred';
    protected $code = 500;
}
