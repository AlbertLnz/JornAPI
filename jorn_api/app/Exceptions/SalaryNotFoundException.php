<?php

namespace App\Exceptions;

use Exception;

class SalaryNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('Salary not found', 404);
    }
}
