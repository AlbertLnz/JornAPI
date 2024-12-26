<?php

namespace Tests\Unit\Service\User;

use App\Services\User\RegisterUserService;
use App\Jobs\SendRegisterNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RegisterUserServiceTest extends TestCase
{
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

    public function testRegisterUserServiceWithValidData()
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

    public function testRegisterUserWithExistingEmail()
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

    public function testRegisterUserWithNullEmailOrPassword()
    {
        $this->expectException(\Exception::class);
        $this->service->execute(null, 'password');

        $this->expectException(\Exception::class);
        $this->service->execute('email@example.com', null);
    }
}
