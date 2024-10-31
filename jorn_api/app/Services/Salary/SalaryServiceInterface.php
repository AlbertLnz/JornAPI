<?php 
namespace App\Services\Salary;

use Carbon\Carbon;

interface SalaryServiceInterface{

    public function execute(string $employeeId, string $date);
}