<?php

namespace Tests\Unit\Service\User;

use App\Models\User;
use App\Services\User\UserUpdateService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UpdateUserServiceTest extends TestCase{
use DatabaseTransactions;
private UserUpdateService $service;
private User $user;
public function setUp(): void
{
    parent::setUp();
    $this->service = new UserUpdateService();
    $this->user = User::factory()->create();
}

public function testCanInstantiate()
{
    $this->assertInstanceOf(UserUpdateService::class, $this->service);    

}

public function testUpdateUserServicewithvaliddata()
{
   
    $user = $this->service->execute('peter@example.com','password', $this->user->id);
    $this->assertInstanceOf(User::class, $user);
    $this->assertEquals('peter@example.com', $user->email);
    $this->assertNotEmpty($user->password);
}
}