<?php

namespace App\Exceptions;

use Exception;

class UserIsNotActiveException extends Exception
{
    public function __construct()
    {
        parent::__construct('User is not active. Please contact support', 401);
    }
}
