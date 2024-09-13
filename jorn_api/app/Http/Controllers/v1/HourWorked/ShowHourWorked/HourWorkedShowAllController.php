<?php 

namespace App\Http\Controllers\v1\HourWorked\ShowHourWorked;

use App\DTO\HourWorked\HourWorkedShowDTO;
use Illuminate\Http\Request;

class HourWorkedShowAllController{


    public function __invoke(Request $request){

        
        $user = $request->get('user');
        
        $employee = $user->employee;
        $hourWorkeds = $employee->hourWorkeds;
       /// var_dump($hourWorkeds);
        

        return response()->json(['message' => 'Show all hour worked', 'hourworked' => $hourWorkeds->map(function($hourWorked){
            return HourWorkedShowDTO::fromHourWorked($hourWorked);
        })], 200);
    }
}