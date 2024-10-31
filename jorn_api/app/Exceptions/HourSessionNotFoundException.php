<?php

namespace App\Exceptions;

use Exception;

class HourSessionNotFoundException extends Exception
{
    public function __construct(){
        parent::__construct("Hour Session not found", 404);
    }
}
