<?php 

namespace App\Http\Controllers\v1\HourWorked\UpdateHourWorked;

use App\Exceptions\HourWorkedNotFoundException;
use App\Http\Requests\HourWorkedUpdateRequest;
use App\Services\HourWorked\HourWorkedUpdateService;

class HourWorkedUpdateController{


    public function __construct(private HourWorkedUpdateService $hourWorkedUpdateService){}

    public function __invoke(HourWorkedUpdateRequest $request)
    {
       try{ 
        
        $user = $request->user();

        $employee = $user->employee;
      $hourWorked =  $this->hourWorkedUpdateService->execute(
        $employee->id,
        $request->query('date'),
        $request->start_time,
        $request->end_time, 
        $request->planned_hours, 
        $request->is_holiday, 
        $request->is_overtime);
        return response()->json(['message' => 'Hour worked updated successfully', 'hourworked' => $hourWorked], 200);
       }catch(HourWorkedNotFoundException $exception){

        return response()->json(['message' => $exception->getMessage()], $exception->getCode());
       }
    }
}