<?php

namespace App\Exceptions;

use Exception;

class HourWorkedExistException extends Exception
{
    public function __construct(){
        parent::__construct("Hour worked already exist");
    }
}
