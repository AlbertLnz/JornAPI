<?php 
namespace Tests\Unit\Service\Auth;
use App\Services\Auth\AuthService;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Exceptions\UserNotFound;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\UnauthorizedException;
use Ramsey\Uuid\Uuid as UuidUuid;

class AuthServiceTest extends TestCase
{
    use DatabaseTransactions;
    private $authService;
    private $tokenService;
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService();
        $this->authService = new AuthService($this->tokenService);
        $this->user = new User();
    }

    public function testCantInstantiate(){
        $this->assertInstanceOf(AuthService::class, $this->authService);
    }

    public function testUserNotFound()
    {
        $this->expectException(UserNotFound::class);
        $this->authService->execute('non-existent-email@example.com', 'password');
    }

    public function testUserNotActive()
    {
        $data = [
            'id' => UuidUuid::uuid4()->toString(),
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ];
        $user =User::create($data);
     
      
        $user->is_active = false;
       $user->save();
        $this->expectException(UnauthorizedException::class);
        $this->authService->execute('user@example.com', 'password');
    }
}