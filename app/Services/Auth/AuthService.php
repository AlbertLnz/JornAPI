<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Exceptions\UserIsNotActiveException;
use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    /**
     * Summary of __construct
     */
    public function __construct(private TokenService $jwtService) {}

    /**
     * Summary of execute
     */
    public function execute(string $email, string $password): array
    {
        $user = $this->validateUser($email, $password);
        $token = Cache::store('redis')->remember("user:{$user->id}:token", 3600, function () use ($user) {
            return $this->generateToken($user);
        });

        return ['token' => $token];
       
        
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
            throw new UserIsNotActiveException;
        }

        if (! Hash::check($password, $user->password)) {
            throw new UnauthorizedException('Invalid credentials.', 401);
        }

        return $user;
    }

    /**
     * Genera el token y el refresh token para el usuario.
     */
    private function generateToken(User $user): string
    {
        return $this->jwtService->generateToken($user->id);

    }

    /**
     * Summary of RedisExists
     */
   
}
