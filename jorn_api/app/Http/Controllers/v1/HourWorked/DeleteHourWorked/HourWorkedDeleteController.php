<?php 

namespace App\Http\Controllers\v1\HourWorked\DeleteHourWorked;

use App\Exceptions\HourWorkedNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\HourWorked\HourWorkedDeleteService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class HourWorkedDeleteController extends Controller
{
    public function __construct(private HourWorkedDeleteService $hourWorkedDeleteService){}
    public function __invoke(Request $request)
    {
        try{
            $query = $request->query('date');
            $user = $request->user();
            $employee = $user->employee;
            $this->hourWorkedDeleteService->execute($employee->id, $query);
            return response()->json(['message' => 'Hour worked deleted successfully'], 200);

        }catch(HourWorkedNotFoundException $exception){
            throw new HttpResponseException(response()->json(['message' => $exception->getMessage()], $exception->getCode()));
        }
      
    }
}