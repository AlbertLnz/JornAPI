<?php

declare(strict_types=1);

namespace App\Services\Salary;

interface SalaryServiceInterface
{
    /**
     * Summary of execute
     */
    public function execute(string $employeeId, string $date): void;
}
