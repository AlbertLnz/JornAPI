<?php

namespace App\Exceptions;

use Exception;

class HourSessionNotFoundException extends Exception
{
    public function __construct(){
        parent::__construct("Hour worked not found", 404);
    }
}
