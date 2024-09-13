<?php
declare(strict_types=1);

namespace App\Services\Token;

use App\Exceptions\InvalidTokenException;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Passport\Exceptions\InvalidAuthTokenException;
class TokenService
{
    protected $secret;

    public function __construct()
    {
        $this->secret = config('jwt.secret');
    }

    public function generateToken($userId, $roles = [])
    {
        $payload = [
            'sub' => $userId,
            'role' => $roles[0]['name'],
            'iat' => time(),
            'exp' => time() + 3600, // Token válido por 30 minutos
            'jti' => bin2hex(random_bytes(16)) // Genera un ID único para el token
        ];
    
        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function decodeToken($token): ?object
    {
        try {
             $tokenDecoded = JWT::decode($token, new Key($this->secret, 'HS256'));
          
             return $tokenDecoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getJtiFromToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
            return $decoded->jti;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function generateRefreshToken($userId)
    {
        $refreshToken = Str::random(60); // Genera un refresh token aleatorio
        
        // Establece la fecha de expiración para 1 día a partir de ahora
        $expiresAt = Carbon::now()->addDay();

        // Guarda el refresh token en la base de datos
        DB::table('jwt_refresh_tokens')->insert([
            'id' => Str::uuid(),
            'user_id' => $userId,
            'refresh_token' => $refreshToken,
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        return $refreshToken;
    }

    // Nueva función para validar el refresh token
    public function validateRefreshToken($userId, $refreshToken)
    {
        $record = DB::table('jwt_refresh_tokens')
            ->where('user_id', $userId)
            ->where('refresh_token', $refreshToken)
            ->first();

        if ($record && Carbon::now()->lessThanOrEqualTo($record->expires_at)) {
            return true;
        }

        return false;
    }

    // Opcional: función para revocar un refresh token (por ejemplo, al cerrar sesión)
    public function revokeRefreshToken($userId, $refreshToken)
    {
        DB::table('jwt_refresh_tokens')
            ->where('user_id', $userId)
            ->where('refresh_token', $refreshToken)
            ->delete();
    }

    // Opcional: función para revocar todos los refresh tokens de un usuario (ej. logout)
    public function revokeAllRefreshTokens($userId)
    {
        DB::table('jwt_refresh_tokens')
            ->where('user_id', $userId)
            ->delete();
    }
}
