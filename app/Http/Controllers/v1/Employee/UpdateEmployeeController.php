<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\Employee;

use App\DTO\Employee\ShowEmployeeDTO;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Services\Employee\EmployeeUpdateService;
use App\Services\Token\TokenService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateEmployeeController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Employee\EmployeeUpdateService $employeeUpdateService
     */
    public function __construct(private EmployeeUpdateService $employeeUpdateService){

    }
    /**
     * Summary of __invoke
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateEmployeeRequest $request):JsonResponse{
        try{
            $user = $request->user();
            $employee= $this->employeeUpdateService->execute($request->name, 
                                                $request->company, 
                                                $request->normal_hourly_rate, 
                                                $request->overtime_hourly_rate, 
                                                $request->holiday_hourly_rate, 
                                                $request->irpf, 
                                                $user->id);
             
             return response()->json(['message' => 'Employee updated successfully','employee'=>ShowEmployeeDTO::fromEmployee($employee)], 200);
        }catch(UserNotFound $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }
      
    }
}
