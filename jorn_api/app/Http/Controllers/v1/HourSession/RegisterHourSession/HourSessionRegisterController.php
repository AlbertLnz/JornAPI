<?php
namespace App\Http\Controllers\v1\HourSession\RegisterHourSession;


use App\Exceptions\HourSessionExistException;
use App\Exceptions\TimeEntryException;
use App\Exceptions\TodayDateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\HourSessionRegisterRequest;
use App\Services\HourSession\HourSessionRegisterService;

class HourSessionRegisterController extends Controller
{
    public function  __construct(private HourSessionRegisterService $HourSessionRegisterService,){}

    public function __invoke(HourSessionRegisterRequest $request)    {

        try{
            $user =$request->user();

            $employee = $user->employee;
            
            $this->HourSessionRegisterService->execute(          
            $employee->id,
            $request->date,
            $request->start_time,
            $request->end_time, 
            $request->planned_hours, 
            $request->is_holiday, 
            $request->is_overtime);
        return response()->json(['message' => 'Hour worked registered successfully'], 201);
        }catch(HourSessionExistException | TodayDateException| TimeEntryException $exception){
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
       
    }

   


    
}
