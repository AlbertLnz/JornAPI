<?php

namespace App\Exceptions;

use Exception;

class TimeEntryException extends Exception
{
    
    public function __construct($msg ){
        parent::__construct($msg, 400);
    }
}
