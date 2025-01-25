<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckBlockedIpRedisMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $ip = $request->ip();
        if (Cache::has("blocked_ip_{$ip}")) {
            throw new HttpResponseException(response(['message' => 'Too Many Attempts. Locked for 30 minutes, try again later'], 429));
        }
        return $next($request);
    }
}
