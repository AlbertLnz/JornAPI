<?php 

namespace Tests\Unit\Service\User;

use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\User\FindUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class FindUserServiceTest extends TestCase{

use DatabaseTransactions;
    private FindUserService $service;
    private User $user;
    public function setUp(): void
    {
        
        parent::setUp();
        $this->service = new FindUserService();
        $this->user = User::factory()->create();
    }
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(FindUserService::class, $this->service);    
    }

    public function testFindUserServicewithvaliddata(){

        $user = $this->service->execute($this->user->id);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testUserNotFound()
    {
        $this->expectException(UserNotFound::class);
        $this->service->execute(Uuid::uuid4()->toString());
    }
}