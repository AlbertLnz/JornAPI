<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\Auth;

use App\Exceptions\InvalidTokenException;
use App\Http\Controllers\Controller;
use App\Services\Token\TokenService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
/**
 * Summary of LogOutController
 */
class LogOutController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\Token\TokenService $tokenService
     */
    public function __construct(private TokenService $tokenService){}
    
    /**
     * Summary of __invoke
     * @param \Illuminate\Http\Request $request
     * @throws \App\Exceptions\InvalidTokenException
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request):JsonResponse
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
