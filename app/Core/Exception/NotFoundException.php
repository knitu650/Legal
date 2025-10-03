<?php

namespace App\Core\Exception;

class NotFoundException extends \Exception
{
    protected $message = 'Resource not found';
    protected $code = 404;
}
