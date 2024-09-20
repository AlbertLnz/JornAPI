<?php 

namespace App\Services\HourSession;

use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HourSessionDeleteService{
    public function __construct(){}

    public function execute(string $employeeId, string $date): void
    {
        $HourSession = HourSession::where('employee_id', $employeeId)->where('date', $date)->first();
        if(!$HourSession){
            throw new HourSessionNotFoundException();
        }
        $HourSession->delete();


    }
}