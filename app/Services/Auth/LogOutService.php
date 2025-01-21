<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Exceptions\InvalidTokenException;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\Redis;

class LogOutService
{
    public function __construct(private TokenService $tokenService) {}

    /**
     * Summary of logOut
     *
     *
     * @throws \App\Exceptions\InvalidTokenException
     */
    public function logOut(string $token): void
    {
        $jti = $this->tokenService->getJtiFromToken($token);
        $userID = $this->tokenService->decodeToken($token)->sub;

        if (! $jti) {
            throw new InvalidTokenException;
        }

        Redis::del("user:$userID:token");
    }
}
