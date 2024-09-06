<?php

namespace App\Http\Controllers\v1\Auth;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Token\TokenService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginController extends Controller
{
    public function __construct( private AuthService $authService)
    {
        
    }
    public function __invoke(Request $request){

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
