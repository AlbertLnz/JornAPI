<?php

namespace Tests\Unit\Service\User;

use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\User\FindUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class FindUserServiceTest extends TestCase
{
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

    public function testFindUserServiceWithValidData()
    {
        // Ejecuta el servicio con el ID de usuario
        $userArray = $this->service->execute($this->user->id);
        
        // Asegúrate de que el resultado sea un array
        $this->assertIsArray($userArray);
        
        // Asegúrate de que el array tiene las claves que esperas de la DTO (en este caso, se usa 'toArray')
        $this->assertArrayHasKey('id', $userArray);
        $this->assertArrayHasKey('email', $userArray);
        // Añade más claves si es necesario, según las propiedades del UserDTO.
    }

    public function testUserNotFound()
    {
        // Simula la excepción cuando el usuario no se encuentra
        $this->expectException(UserNotFound::class);
        
        // Pasa un UUID que no existe
        $this->service->execute(Uuid::uuid4()->toString());
    }
}
