<?php 
declare(strict_types=1);

namespace Tests\Unit\Service\User;

use App\Models\User;
use App\Services\User\DeleteUserService;
use Tests\TestCase;

class DeleteUserServiceTest extends TestCase{

    private DeleteUserService $service;
    private User $user;
    public function setUp(): void
    {
        parent::setUp();
        
        $this->service = new DeleteUserService();
        $this->user = User::factory()->create();
    }
    public function testCanInstantiate()
    {
        $this->assertInstanceOf(DeleteUserService::class, $this->service);    
    }

    public function testDeleteUserServicewithvaliddata(){
 $this->service->execute($this->user->id);
        $user = User::where('id', $this->user->id)->first();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(0, $user->is_active);
    }
}