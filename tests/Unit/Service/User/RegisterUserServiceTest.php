<?php

namespace Tests\Unit\Service\User;

use App\Models\User;
use App\Services\User\RegisterUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RegisterUserServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RegisterUserService;
    }

    public function test_can_instantiate()
    {
        $this->assertInstanceOf(RegisterUserService::class, $this->service);
    }

    public function test_register_user_service_with_valid_data()
    {
        // Asegurarse de que el trabajo no se ejecute realmente durante la prueba
        Queue::fake();

        $user = $this->service->execute('email@example.com', 'password');

        // Verificar que el usuario fue creado correctamente
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('email@example.com', $user->email);
        $this->assertNotEmpty($user->password);

        // Verificar que el trabajo fue encolado

    }

    public function test_register_user_with_existing_email()
    {
        // Crear un usuario previamente para asegurar que ya existe
        User::create([
            'email' => 'email@example.com',
            'password' => 'password',
        ]);

        $this->expectException(\App\Exceptions\UserAlreadyExists::class);

        // Intentar registrar un usuario con el mismo email
        $this->service->execute('email@example.com', 'newpassword');
    }

   
    
}
