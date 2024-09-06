<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidTokenException;
use App\Exceptions\TokenNotProvidedException;
use App\Services\Token\TokenService;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class CheckTokenInRedis
{

    public function __construct(private TokenService $tokenService){}
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
 
            $user = \App\Models\User::find($request->attributes->get('user_id'));
            
            // Check the token in Redis
            $cachedToken = Cache::store('redis')->get("user:{$user->id}:token");
    
            if (!$cachedToken || $cachedToken !== $token) {
                throw new InvalidTokenException();
            }
           
            auth()->setUser($user);
    
            return $next($request);

        }catch(InvalidTokenException  $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }
      
    }
}

