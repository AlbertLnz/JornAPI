<?php
namespace App\Http\Controllers\v1\HourWorked\ShowHourWorked;

use App\Exceptions\HourWorkedNotFoundException;
use App\Services\HourWorked\HourWorkedShowService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class HourWorkedShowController
{
    public function __construct(private HourWorkedShowService $hourWorkedShowService){}
    public function __invoke(Request $request)
    {
        try{
            $query = $request->query('date');
            $user = $request->user();
           $hourWorked =  $this->hourWorkedShowService->execute($user->employee->id, $query);
           return response()->json(['hourworked' => $hourWorked], 200);
        }catch(HourWorkedNotFoundException $exception){
            throw new HttpResponseException(response()->json(['message' => $exception->getMessage()], $exception->getCode()));
            
        }
      
    }
}