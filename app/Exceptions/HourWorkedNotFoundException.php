<?php

namespace App\Exceptions;

use Exception;

class HourWorkedNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Hour worked not found, try another date', 404);
    }
}
