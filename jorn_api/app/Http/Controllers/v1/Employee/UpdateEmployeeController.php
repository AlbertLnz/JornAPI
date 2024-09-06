<?php

namespace App\Http\Controllers\v1\Employee;

use App\DTO\Employee\ShowEmployeeDTO;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Services\Employee\EmployeeUpdateService;
use App\Services\Token\TokenService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class UpdateEmployeeController extends Controller
{

    public function __construct(private EmployeeUpdateService $employeeUpdateService, private TokenService $tokenService){

    }
    public function __invoke(Request $request){
        try{
            $decode=  $this->tokenService->decodeToken($request->bearerToken());
            $employee= $this->employeeUpdateService->execute($request->name, 
                                                $request->company, 
                                                $request->normal_hourly_rate, 
                                                $request->overtime_hourly_rate, 
                                                $request->night_hourly_rate, 
                                                $request->holiday_hourly_rate, 
                                                $request->irpf, 
                                                $decode->sub);
             
             return response()->json(['message' => 'Employee updated successfully','employee'=>ShowEmployeeDTO::fromEmployee($employee)], 200);
        }catch(UserNotFound $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }
      
    }
}
