<?php

namespace App\Services\Salary;

interface SalaryServiceInterface
{
    public function execute(string $employeeId, string $date);
}
