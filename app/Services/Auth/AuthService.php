<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\Redis\RedisService;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    /**
     * Summary of __construct
     */
    public function __construct(private TokenService $jwtService, private RedisService $redisService) {}

    /**
     * Summary of execute
     *
     * @throws \App\Exceptions\UserNotFound
     * @throws \Illuminate\Validation\UnauthorizedException
     */
    public function execute(string $email, string $password): array
    {
        $user = $this->validateUser($email, $password);

        $tokens = $this->generateTokens($user);

        // Cachear el token en Redis
    //$l=   Cache::store('redis')->put("user:{$user->id}:token", $tokens['token'], 3600);
    // var_dump($l);
        $this->redisService->set("user:{$user->id}:token", $tokens['token']);

        return $tokens;
    }

    /**
     * Valida el usuario y sus credenciales.
     *
     * @throws UserNotFound
     * @throws UnauthorizedException
     */
    private function validateUser(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw new UserNotFound;
        }

        if (! $user->is_active) {
            throw new UnauthorizedException('User not active. Please contact your administrator.', 401);
        }

        if (! Hash::check($password, $user->password)) {
            throw new UnauthorizedException('Invalid credentials.', 401);
        }

        return $user;
    }

    /**
     * Genera el token y el refresh token para el usuario.
     */
    private function generateTokens(User $user): array
    {
        $token = $this->jwtService->generateToken($user->id);
        $refreshToken = $this->jwtService->generateRefreshToken($user->id);

        return [
            'token' => $token,
            'refreshToken' => $refreshToken,
        ];
    }
}
