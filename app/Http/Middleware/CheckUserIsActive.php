<?php

namespace App\Http\Middleware;

use App\Exceptions\UserIsNotActiveException;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->is_active == 0) {
            throw new HttpResponseException(response()->json([UserIsNotActiveException::class], 403));
        }
        return $next($request);
    }
}
