<?php 

namespace App\Http\Controllers\v1\HourSession\DeleteHourSession;

use App\Exceptions\HourSessionNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\HourSession\HourSessionDeleteService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class HourSessionDeleteController extends Controller
{
    public function __construct(private HourSessionDeleteService $hourSessionDeleteService){}
    public function __invoke(Request $request)
    {
        try{
            $query = $request->query('date');
            $user = $request->user();
            $employee = $user->employee;
            $this->hourSessionDeleteService->execute($employee->id, $query);
            return response()->json(['message' => 'Hour worked deleted successfully'], 200);

        }catch(HourSessionNotFoundException $exception){
            throw new HttpResponseException(response()->json(['message' => $exception->getMessage()], $exception->getCode()));
        }
      
    }
}