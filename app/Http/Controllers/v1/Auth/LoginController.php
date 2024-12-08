<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\Auth;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;

/**
 * Summary of LoginController
 */
class LoginController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Auth\AuthService $authService
     */
    public function __construct( private AuthService $authService)
    {
        
    }
    /**
     * Summary of __invoke
     * @param \App\Http\Requests\LoginRequest $request
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse{

            try{

                $data = $this->authService->execute($request->email, $request->password);
                return response()->json(['token' => $data['token']
                                         ,'refreshToken' => $data['refreshToken'] ], 200);    
            }catch(UserNotFound  | UnauthorizedException $e){
                throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode())); 
            }
          
        }  
}
