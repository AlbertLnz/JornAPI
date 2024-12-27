<?php

namespace Tests\Unit\Service\User;

use App\Models\User;
use App\Services\User\UpdateUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UpdateUserServiceTest extends TestCase{
use DatabaseTransactions;
private UpdateUserService $service;
private User $user;
public function setUp(): void
{
    parent::setUp();
    $this->service = new UpdateUserService();
    $this->user = User::factory()->create();
}

public function testCanInstantiate()
{
    $this->assertInstanceOf(UpdateUserService::class, $this->service);    

}

public function testUpdateUserServicewithvaliddata()
{
   
    $user = $this->service->execute('peter@example.com', $this->user->id);
    $this->assertInstanceOf(User::class, $user);
    $this->assertEquals('peter@example.com', $user->email);
}
}