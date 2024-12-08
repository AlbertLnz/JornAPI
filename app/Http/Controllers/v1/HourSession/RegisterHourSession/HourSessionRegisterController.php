<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\HourSession\RegisterHourSession;

use App\Enums\WorkTypeEnum;
use App\Exceptions\HourSessionExistException;
use App\Exceptions\TimeEntryException;
use App\Exceptions\TodayDateException;
use App\Http\Controllers\Controller;
use App\Http\Requests\HourSessionRegisterRequest;
use App\Services\HourSession\HourSessionRegisterService;
use Illuminate\Http\JsonResponse;


class HourSessionRegisterController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\HourSession\HourSessionRegisterService $hourSessionRegisterService
     */
    public function  __construct(private HourSessionRegisterService $hourSessionRegisterService,){}
    /**
     * Summary of __invoke
     * @param \App\Http\Requests\HourSessionRegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(HourSessionRegisterRequest $request): JsonResponse  {

        try{
            $employee =$request->user()->employee;
            $workType = WorkTypeEnum::fromValue($request->work_type);
            
            
            $this->hourSessionRegisterService->execute(          
            $employee->id,
            $request->date,
            $request->start_time,
            $request->end_time, 
            $request->planned_hours, 
            $workType->value?? null);

        return response()->json(['message' => 'Hour worked registered successfully'], 201);
        }catch(HourSessionExistException | TodayDateException| TimeEntryException $exception){
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
       
    }

   


    
}
