<?php

namespace App\Http\Controllers\v1\Auth;

use App\Exceptions\InvalidTokenException;
use App\Http\Controllers\Controller;
use App\Services\Token\TokenService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LogOutController extends Controller
{
    public function __construct(private TokenService $tokenService){}
    
    public function __invoke(Request $request)
    {

        try{
            $token = $request->bearerToken();
            // Extraer el JWT ID ('jti') del token
            $jti = $this->tokenService->getJtiFromToken($token);
            $userID = $this->tokenService->decodeToken($token)->sub;
   
         
   
            if (!$jti) {
                // Almacenar el 'jti' en Redis para la lista negra (blacklist)
               throw new InvalidTokenException();
            
            }
   
            Cache::store('redis')->put('blacklist:' . $jti, true, now()->addMinutes(60));
            $this->tokenService->revokeAllRefreshTokens($userID);
   
    
                return response()->json(['message' => 'Logged out successfully']);

        }catch(InvalidTokenException $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));

        }
      
 
    }
}
