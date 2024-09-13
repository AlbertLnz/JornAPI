<?php

namespace App\Exceptions;

use Exception;

class TimeEntryException extends Exception
{
    public function __construct(){
        parent::__construct("The start time cannot be greater than the end time");
    }
}
