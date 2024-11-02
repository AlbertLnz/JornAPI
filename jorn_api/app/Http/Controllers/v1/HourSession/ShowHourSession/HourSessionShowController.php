<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\HourSession\ShowHourSession;

use App\Exceptions\HourSessionNotFoundException;
use App\Http\Requests\HourSessionShowRequest;
use App\Services\HourSession\HourSessionShowService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class HourSessionShowController
{
    /**
     * Summary of __construct
     * @param \App\Services\HourSession\HourSessionShowService $hourSessionShowService
     */
    public function __construct(private HourSessionShowService $hourSessionShowService){}
    /**
     * Summary of __invoke
     * @param \App\Http\Requests\HourSessionShowRequest $request
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(HourSessionShowRequest $request):JsonResponse
    {
        try{
            $query = $request->query('date');
           
            $employee = $request->user()->employee;
           $hourSession =  $this->hourSessionShowService->execute($employee->id, $query);
           return response()->json(['HourSession' => $hourSession], 200);
        }catch(HourSessionNotFoundException $exception){
            throw new HttpResponseException(response()->json(['message' => $exception->getMessage()], $exception->getCode()));
            
        }
      
    }
}