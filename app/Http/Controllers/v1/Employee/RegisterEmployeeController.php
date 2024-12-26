<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\Employee;

use App\Exceptions\NullDataException;
use App\Exceptions\UserAlReadyExists;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterEmployeeRequest;
use App\Services\Employee\RegisterEmployeeService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class RegisterEmployeeController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Employee\RegisterEmployeeService $service
     */

    public function __construct(private RegisterEmployeeService $service) {
        
    }
    /**
     * Summary of __invoke
     * @param \App\Http\Requests\RegisterEmployeeRequest $request
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(RegisterEmployeeRequest $request) :JsonResponse
    {
        try{
            $this->service->execute($request->name,
                                     $request->email, 
                                     $request->password, 
                                     $request->normal_hourly_rate, 
                                     $request->overtime_hourly_rate,
                                     $request->holiday_hourly_rate, 
                                     $request->irpf);
            return response()->json(['message' => 'Employee created successfully'], 201);
        }catch(UserAlReadyExists |NullDataException $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));

        }
      
    }


}
