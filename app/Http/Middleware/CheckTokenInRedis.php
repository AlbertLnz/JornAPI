<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidTokenException;
use App\Services\Token\TokenService;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CheckTokenInRedis
{
    public function __construct(private TokenService $tokenService) {}

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->attributes->get('token');

            $user = $request->attributes->get('user');

            // Check the token in Redis
         $cachedToken = Redis::get("user:{$user->id}:token");
            

            if (! $cachedToken || $cachedToken !== $token) {
                throw new InvalidTokenException;
            }

            auth()->setUser($user);

            return $next($request);

        } catch (InvalidTokenException  $e) {
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }

    }
}
