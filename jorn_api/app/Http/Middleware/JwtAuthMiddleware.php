<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidTokenException;
use App\Exceptions\TokenNotProvidedException;
use Closure;
use Illuminate\Http\Request;
use App\Services\Token\TokenService;
use Illuminate\Http\Exceptions\HttpResponseException;

class JwtAuthMiddleware
{
    protected $jwtService;

    public function __construct(TokenService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $token = $request->bearerToken();

            if (!$token) {
                throw new TokenNotProvidedException();
            }
    
            $decoded = $this->jwtService->decodeToken($token);
    
            if (!$decoded) {
                throw new InvalidTokenException();
            }
    
            $user = \App\Models\User::find($decoded->sub);
            auth()->setUser($user);
            $request->attributes->set('user_id', $decoded->sub);
            $request->attributes->set('role', $decoded->role);
    
            return $next($request);

        }catch(InvalidTokenException | TokenNotProvidedException  $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }
       
    }
}
