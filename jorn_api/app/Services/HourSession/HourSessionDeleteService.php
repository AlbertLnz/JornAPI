<?php 
declare(strict_types=1);
namespace App\Services\HourSession;

use App\Exceptions\HourSessionNotFoundException;
use App\Jobs\ProcessSalary;
use App\Models\HourSession;


class HourSessionDeleteService{
    public function __construct(){}
    /**
     * Summary of execute
     * @param string $employeeId
     * @param string $date
     * @throws \App\Exceptions\HourSessionNotFoundException
     * @return void
     */
    public function execute(string $employeeId, string $date): void
    {
        $hourSession = HourSession::where('employee_id', $employeeId)->where('date', $date)->first();
        if(!$hourSession){
            throw new HourSessionNotFoundException();
        }
        $hourSession->delete();

        ProcessSalary::dispatch($employeeId, $date);


    }
}