<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Exceptions\InvalidTokenException;
use App\Services\Redis\RedisService;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\Cache;

class LogOutService
{
    public function __construct(private TokenService $tokenService, private RedisService $redisService) {}

    /**
     * Summary of logOut
     *
     * @param  string  $userId
     * @return void
     *
     * @throws \App\Exceptions\InvalidTokenException
     */
    public function logOut(string $token)
    {
        $jti = $this->tokenService->getJtiFromToken($token);
        $userID = $this->tokenService->decodeToken($token)->sub;

        if (! $jti) {
            throw new InvalidTokenException;
        }

        Cache::store('redis')->put("blacklist:$jti", true, now()->addMinutes(30));
        $this->redisService->delete("user:$userID:token");
        $this->tokenService->revokeAllRefreshTokens($userID);
    }
}
