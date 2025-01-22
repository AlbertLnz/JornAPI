<?php

namespace Tests\Unit\Service\Auth;

use App\Exceptions\UserIsNotActiveException;
use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Ramsey\Uuid\Uuid as UuidUuid;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $authService;

    private $tokenService;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService;
        $this->authService = new AuthService($this->tokenService);
        $this->user = new User;
    }

    public function test_cant_instantiate()
    {
        $this->assertInstanceOf(AuthService::class, $this->authService);
    }

    public function test_user_not_found()
    {
        $this->expectException(UserNotFound::class);
        $this->authService->execute('non-existent-email@example.com', 'password');
    }

    public function test_user_not_active()
    {
        $data = [
            'id' => UuidUuid::uuid4()->toString(),
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ];
        $user = User::create($data);

        $user->is_active = false;
        $user->save();
        $this->expectException(UserIsNotActiveException::class);
        $this->authService->execute('user@example.com', 'password');
    }
}
