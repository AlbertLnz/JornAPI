<?php 
declare(strict_types=1);
namespace App\Http\Controllers\v1\HourSession\DeleteHourSession;

use App\Exceptions\HourSessionNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\HourSession\HourSessionDeleteService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HourSessionDeleteController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\HourSession\HourSessionDeleteService $hourSessionDeleteService
     */
    public function __construct(private HourSessionDeleteService $hourSessionDeleteService){}
    /**
     * Summary of __invoke
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request):JsonResponse
    {
        try{
            $query = $request->query('date');
            $employee = $request->user()->employee;
            $this->hourSessionDeleteService->execute($employee->id, $query);
            return response()->json(['message' => 'Hour worked deleted successfully'], 200);

        }catch(HourSessionNotFoundException $exception){
            throw new HttpResponseException(response()->json(['message' => $exception->getMessage()], $exception->getCode()));
        }
      
    }
}