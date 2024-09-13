<?php

namespace App\Http\Middleware;

use App\Services\Token\TokenService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class JwtBlackListMiddleware
{

    public function __construct(private TokenService $tokenService){}
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        // Extraer el JWT ID ('jti') del token
        $jti = $this->tokenService->getJtiFromToken($token);

        if (Cache::store('redis')->has('blacklist:' . $jti)) {
            return response()->json(['error' => 'Token has been revoked'], 401);
        }

   
        return $next($request);
    }
}
