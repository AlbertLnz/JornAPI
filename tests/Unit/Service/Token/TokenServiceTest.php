<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Token;

use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TokenServiceTest extends TestCase
{
    private TokenService $service;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TokenService;
        $this->user = User::factory()->create();
        $this->user->assignRole('employee');
    }

    public function test_can_instantiate()
    {
        $this->assertInstanceOf(TokenService::class, $this->service);
    }

    public function test_token_servicewithvaliddata()
    {
        $token = $this->service->generateToken($this->user->id, $this->user->roles);
        $this->assertNotEmpty($token);
    }

    public function test_decode_token()
    {
        $token = $this->service->generateToken($this->user->id, $this->user->roles);
        $decode = $this->service->decodeToken($token);
        $this->assertNotEmpty($decode);
        $this->assertEquals($this->user->id, $decode->sub);
    }

    public function test_generate_refresh_token()
    {
        $token = $this->service->generateRefreshToken($this->user->id);
        $this->assertNotEmpty($token);
    }

    public function test_get_jti()
    {
        $token = $this->service->generateToken($this->user->id, $this->user->roles);
        $jti = $this->service->getJtiFromToken($token);
        $this->assertNotEmpty($jti);
    }

    public function revokeRefreshToken()
    {
        $token = $this->service->generateRefreshToken($this->user->id);
        $this->service->revokeRefreshToken($this->user->id, $token);
        $refreshToken = DB::table('jwt_refresh_tokens')->where('token', $token)->first();
        $this->assertNull($refreshToken);

    }

    public function validateRefreshToken()
    {
        $token = $this->service->generateRefreshToken($this->user->id);
        $this->service->validateRefreshToken($this->user->id, $token);
        $this->assertTrue($this->service->validateRefreshToken($this->user->id, $token));
    }
}
