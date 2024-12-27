<?php

namespace App\Exceptions;

use Exception;

class TodayDateException extends Exception
{
    public function __construct()
    {
        parent::__construct('Date not today', 400);
    }
}
