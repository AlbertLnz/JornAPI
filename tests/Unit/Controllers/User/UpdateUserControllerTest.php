<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\User;

use App\DTO\User\UserDTO;
use App\Http\Controllers\v1\User\UpdateUserController;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\Token\TokenService;
use App\Services\User\UpdateUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class UpdateUserControllerTest extends TestCase
{
    use DatabaseTransactions;

    private UpdateUserController $controller;

    private User $user;

    private TokenService $tokenService;

    private UpdateUserService $userUpdateService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userUpdateService = Mockery::mock(UpdateUserService::class);
        $this->controller = new UpdateUserController($this->userUpdateService);
        $this->user = User::factory()->create();
        $this->user->assignRole('employee');
    }

    public function test_can_instantiate()
    {
        $this->assertInstanceOf(UpdateUserController::class, $this->controller);
    }

    public function test_update_user()
    {
        // Crear un usuario real en la base de datos de prueba
        $user = User::factory()->create();
        $userId = $user->id;

        // Simular un Request con datos actualizados
        $request = Mockery::mock(UpdateUserRequest::class)->makePartial();
        $request->shouldReceive('user')->andReturn($user);
        $request->email = 'updated_email@example.com';

        // Configurar el servicio simulado
        $this->userUpdateService->shouldReceive('execute')
            ->once()
            ->with('updated_email@example.com', $userId)
            ->andReturn($user);

        // Invocar el controlador
        $response = $this->controller->__invoke($request);

        // Verificar que la respuesta sea una JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Decodificar la respuesta JSON
        $responseData = $response->getData();

        // Verificar el mensaje de la respuesta
        $this->assertEquals('User updated successfully', $responseData->message);
        $this->assertEquals(200, $response->getStatusCode());

        // Verificar que los datos del usuario coincidan
        $this->assertEquals(UserDTO::toArray($user->toArray()), (array) $responseData->user);
    }

    public function test_update_user_with_null_email()
    {
        // Crear un usuario real en la base de datos de prueba
        $user = User::factory()->create();
        $userId = $user->id;

        // Simular un Request con datos actualizados
        $request = Mockery::mock(UpdateUserRequest::class)->makePartial();
        $request->shouldReceive('user')->andReturn($user);
        $request->email = null;

        // Configurar el servicio simulado
        $this->userUpdateService->shouldReceive('execute')
            ->once()
            ->with(null, $userId)
            ->andReturn($user);

        // Invocar el controlador
        $response = $this->controller->__invoke($request);

        // Verificar que la respuesta sea una JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Decodificar la respuesta JSON
        $responseData = $response->getData();

        // Verificar el mensaje de la respuesta
        $this->assertEquals('User updated successfully', $responseData->message);
        $this->assertEquals(200, $response->getStatusCode());

        // Verificar que los datos del usuario coincidan
        $this->assertEquals(UserDTO::toArray($user->toArray()), (array) $responseData->user);
    }
}
