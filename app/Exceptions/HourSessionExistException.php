<?php

namespace App\Exceptions;

use Exception;

class HourSessionExistException extends Exception
{
    public function __construct()
    {
        parent::__construct('Hour worked already exist', 409);
    }
}
