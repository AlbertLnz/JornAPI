<?php
namespace App\Http\Controllers\v1\HourWorked\RegisterHourWorked;


use App\Exceptions\HourWorkedExistException;
use App\Exceptions\TimeEntryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\HourWorkedRegisterRequest;
use App\Services\HourWorked\HourWorkedRegisterService;

class HourWorkedRegisterController extends Controller
{
    public function  __construct(private HourWorkedRegisterService $hourWorkedRegisterService,){}

    public function __invoke(HourWorkedRegisterRequest $request)    {

        try{
            $user =$request->user();

            $employee = $user->employee;
            
    
            $this->hourWorkedRegisterService->execute(          
            $employee->id,
            $request->date,
            $request->start_time,
            $request->end_time, 
            $request->planned_hours, 
            $request->is_holiday, 
            $request->is_overtime);
        return response()->json(['message' => 'Hour worked registered successfully'], 201);
        }catch(HourWorkedExistException | TimeEntryException $exception){
            return response()->json(['message' => $exception->getMessage()], 500);
        }
       
    }

   


    
}
