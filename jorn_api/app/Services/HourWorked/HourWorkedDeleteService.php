<?php 

namespace App\Services\HourWorked;

use App\Exceptions\HourWorkedNotFoundException;
use App\Models\HourWorked;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HourWorkedDeleteService{
    public function __construct(){}

    public function execute(string $employeeId, string $date): void
    {
        $hourWorked = HourWorked::where('employee_id', $employeeId)->where('date', $date)->first();
        if(!$hourWorked){
            throw new HourWorkedNotFoundException();
        }
        $hourWorked->delete();


    }
}