<?php 

namespace App\Http\Controllers\v1\HourSession\UpdateHourSession;

use App\Exceptions\HourSessionNotFoundException;
use App\Http\Requests\HourSessionUpdateRequest;
use App\Services\HourSession\HourSessionUpdateService;

class HourSessionUpdateController{


    public function __construct(private HourSessionUpdateService $hourSessionUpdateService){}

    public function __invoke(HourSessionUpdateRequest $request)
    {
       try{ 
        
        $user = $request->user();

        $employee = $user->employee;
      $hourSession =  $this->HourSessionUpdateService->execute(
        $employee->id,
        $request->query('date'),
        $request->start_time,
        $request->end_time, 
        $request->planned_hours, 
        $request->is_holiday, 
        $request->is_overtime);
        return response()->json(['message' => 'Hour worked updated successfully', 'HourSession' => $hourSession], 200);
       }catch(HourSessionNotFoundException $exception){

        return response()->json(['message' => $exception->getMessage()], $exception->getCode());
       }
    }
}