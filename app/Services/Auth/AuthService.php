<?php
declare(strict_types=1);
namespace App\Services\Auth;

use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class AuthService{
    /**
     * Summary of __construct
     * @param \App\Services\Token\TokenService $jwtService
     */
    public function __construct(private TokenService $jwtService){}
    /**
     * Summary of execute
     * @param string $email
     * @param string $password
     * @throws \App\Exceptions\UserNotFound
     * @throws \Illuminate\Validation\UnauthorizedException
     * @return array
     */
    public function execute(string $email, string $password): array
    {
        $user = $this->validateUser($email, $password);
        
        $tokens = $this->generateTokens($user);
    
        // Cachear el token en Redis
        Cache::store('redis')->put("user:{$user->id}:token", $tokens['token'], 3600);
    
        return $tokens;
    }
    
    /**
     * Valida el usuario y sus credenciales.
     *
     * @param string $email
     * @param string $password
     * @return User
     * @throws UserNotFound
     * @throws UnauthorizedException
     */
    private function validateUser(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            throw new UserNotFound();
        }
    
        if (!$user->is_active) {
            throw new UnauthorizedException('User not active. Please contact your administrator.', 401);
        }
    
        if (!Hash::check($password, $user->password)) {
            throw new UnauthorizedException('Invalid credentials.', 401);
        }
    
        return $user;
    }
    
    /**
     * Genera el token y el refresh token para el usuario.
     *
     * @param User $user
     * @return array
     */
    private function generateTokens(User $user): array
    {
        $token = $this->jwtService->generateToken($user->id, $user->roles);
        $refreshToken = $this->jwtService->generateRefreshToken($user->id);
    
        return [
            'token' => $token,
            'refreshToken' => $refreshToken,
        ];
    }
    
}