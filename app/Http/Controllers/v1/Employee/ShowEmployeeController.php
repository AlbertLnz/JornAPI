<?php

namespace App\Http\Controllers\v1\Employee;

use App\DTO\Employee\ShowEmployeeDTO;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\Token\TokenService;
use App\Services\User\FindUserService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ShowEmployeeController extends Controller
{

    public function __construct(private TokenService $tokenService){}
    public function __invoke(Request $request){
        try{
            $decode= $this->tokenService->decodeToken($request->bearerToken());
            $user= FindUserService::execute($decode->sub);
            $employee = $user->employee;
            if(!$employee){
                throw new UserNotFound();
            }
            return response()->json(['message' => 'Employee found successfully','employee'=>ShowEmployeeDTO::fromEmployee($employee)], 200);
        }catch(UserNotFound $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));

        }
     
    }
}
