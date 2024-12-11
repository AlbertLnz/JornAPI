<?php

namespace App\Http\Controllers\v1\Employee;

use App\DTO\Employee\EmployeeDTO;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowEmployeeController extends Controller
{
    /**
     * Summary of __construct
     */
    public function __construct( ){}
    /**
     * Summary of __invoke
     * @param \Illuminate\Http\Request $request
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request):JsonResponse{
        try{
            $user= $request->user();
        
            
            return response()->json(['message' => 'Employee found successfully','employee'=>EmployeeDTO::fromModel($user->employee)], 200);
        }catch(UserNotFound $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));

        }
     
    }
    //como crear un directorio en linux con terminal y darle permisos de ejecucion con chmod +x directorio y darle permisos de ejecucion con chmod 777 directorio

}
