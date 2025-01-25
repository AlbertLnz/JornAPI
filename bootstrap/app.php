<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Cache;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/v1/v1.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'token_redis' => App\Http\Middleware\CheckTokenInRedis::class,
            'jwt.auth' => \App\Http\Middleware\JwtAuthMiddleware::class,
            'is_active' => App\Http\Middleware\CheckUserIsActive::class,
            'ip_block' => App\Http\Middleware\CheckBlockedIpRedisMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ThrottleRequestsException $e, $request) {
$ip = $request->ip();
            Cache::put("blocked_ip_{$ip}", true, now()->addMinutes(30));
            return response(['message' => 'Too Many Attempts. Locked for 30 minutes, try again later'], 429);
        });
    })->create();
