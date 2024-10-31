<?php
namespace App\Http\Controllers\v1\HourSession\ShowHourSession;

use App\Exceptions\HourSessionNotFoundException;
use App\Http\Requests\HourSessionShowRequest;
use App\Services\HourSession\HourSessionShowService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class HourSessionShowController
{
    public function __construct(private HourSessionShowService $HourSessionShowService){}
    public function __invoke(HourSessionShowRequest $request)
    {
        try{
            $query = $request->query('date');
           
            $user = $request->user();
           $HourSession =  $this->HourSessionShowService->execute($user->employee->id, $query);
           return response()->json(['HourSession' => $HourSession], 200);
        }catch(HourSessionNotFoundException $exception){
            throw new HttpResponseException(response()->json(['message' => $exception->getMessage()], $exception->getCode()));
            
        }
      
    }
}