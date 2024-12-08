<?php

namespace Tests\Unit\Service\User;

use App\Services\User\RegisterUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterUserServiceTest extends TestCase{
use DatabaseTransactions;
    private $service;
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new RegisterUserService();
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(RegisterUserService::class, $this->service);    
    }
    
    public function testregisterUserServicewithvaliddata()
    {
        $this->service = new RegisterUserService();

        $user = $this->service->execute('email@example.com', 'password');

        $this->assertInstanceOf(\App\Models\User::class, $user);
        $this->assertEquals('email@example.com', $user->email);
        $this->assertNotEmpty($user->password);
    }



}