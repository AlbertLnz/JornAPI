<?php 

namespace App\Http\Controllers\v1\HourSession\ShowHourSession;

use App\DTO\HourSession\HourSessionShowDTO;
use Illuminate\Http\Request;

class HourSessionShowAllController{


    public function __invoke(Request $request){

        
        $user = $request->get('user');
        
        $employee = $user->employee;
        $HourSessions = $employee->HourSessions;
       /// var_dump($HourSessions);
        

        return response()->json(['message' => 'Show all hour worked', 'HourSession' => $HourSessions->map(function($HourSession){
            return HourSessionShowDTO::fromHourSession($HourSession);
        })], 200);
    }
}