<?php

namespace App\Http\Controllers\v1\Auth;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginController extends Controller
{
    public function __construct( private AuthService $authService)
    {
        
    }
    public function __invoke(LoginRequest $request){

            try{

                $data = $this->authService->execute($request->email, $request->password);
    
        
                return response()->json(['token' => $data['token']
                                         ,'refreshToken' => $data['refreshToken']
                                                                            ], 200);    

            }catch(UserNotFound  | UnauthorizedException $e){
                throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
            
            }
          
        }
    
}
