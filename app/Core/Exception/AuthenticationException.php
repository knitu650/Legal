<?php

namespace App\Core\Exception;

class AuthenticationException extends \Exception
{
    protected $message = 'Unauthenticated';
    protected $code = 401;
}
