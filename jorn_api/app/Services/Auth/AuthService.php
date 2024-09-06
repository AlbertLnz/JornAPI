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

    public function __construct(private TokenService $jwtService){}

    public function execute(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();
        if(!$user){
            throw new UserNotFound();
        }
        if(!$user->is_active){

            throw new UnauthorizedException('User not active, please contact your administrator',401);
        }

        if (!$user || !Hash::check($password, $user->password)) {
            throw new UnauthorizedException('Invalid credentials',401);
        }

        // Generar el token
        $token = $this->jwtService->generateToken($user->id, $user->roles);
        $refreshToken = $this->jwtService->generateRefreshToken($user->id);
        $data = [
            'token' => $token,
            'refreshToken' => $refreshToken
        ];

      Cache::store('redis')->put("user:{$user->id}:token", $token, 3600); //
      return $data;
    }
}