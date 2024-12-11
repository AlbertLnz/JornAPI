<?php
declare(strict_types=1);
namespace App\Services\Auth;

use App\Exceptions\InvalidTokenException;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\Cache;

class LogOutService{
/**
 * 
 * 
 * @param \App\Services\Token\TokenService $tokenService
 */
public function __construct(private TokenService $tokenService){}
/**
 * Summary of logOut
 * @param string $userId
 * @param string $token
 * @throws \App\Exceptions\InvalidTokenException
 * @return void
 */
public function logOut(string $token){
    $jti = $this->tokenService->getJtiFromToken($token);
    $userID = $this->tokenService->decodeToken($token)->sub;

    if (!$jti) {
        // Almacenar el 'jti' en Redis para la lista negra (blacklist)
       throw new InvalidTokenException();
    }

    Cache::store('redis')->put('blacklist:' . $jti, true, now()->addMinutes(60));
    $this->tokenService->revokeAllRefreshTokens($userID);
}

}