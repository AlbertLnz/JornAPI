<?php 

declare(strict_types=1);

namespace Tests\Unit\Service\Employee;

use App\Models\User;
use App\Services\Employee\RegisterEmployeeService;
use App\Services\User\RegisterUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterEmployeeServiceTest extends TestCase{
use DatabaseTransactions;
    private RegisterEmployeeService $service;
    private RegisterUserService $registerUserService;
    private User $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->registerUserService = new RegisterUserService();
        $this->service = new RegisterEmployeeService($this->registerUserService);
        $this->user = User::factory()->create();
    }

    public function testCanInstantiate()
    {   
        $this->assertInstanceOf(RegisterEmployeeService::class, $this->service);    
    }

    public function testregisterEmployeeServicewithvaliddata()
    {
    
        $this->service->execute('peter','peter@peter.com',$this->user->password, 1, 1, 1, 1, 1);

        $user = User::where('email', 'peter@peter.com')->first();
        $user->assignRole('employee');
        $this->assertInstanceOf(User::class, $user);
        
        $this->assertNotEmpty($user->password);

        
    }
}