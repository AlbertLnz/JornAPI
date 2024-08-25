<?php

namespace App\Exceptions;

use Exception;

class UserAlReadyExists extends Exception
{
    public function __construct()
    {
        parent::__construct('User already exists', 409);
    }
}
