<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\User;

use App\Http\Controllers\v1\User\DeleteUserController;
use App\Models\User;
use App\Services\Token\TokenService;
use App\Services\User\DeleteUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class DeleteUserControllerTest extends TestCase
{
    use DatabaseTransactions;

    private DeleteUserController $controller;

    private User $user;

    private TokenService $tokenService;

    private DeleteUserService $userDeleteService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tokenService = Mockery::mock(TokenService::class);
        $this->userDeleteService = Mockery::mock(DeleteUserService::class);
        $this->controller = new DeleteUserController($this->userDeleteService);

        $this->user = User::factory()->create();
        $this->user->assignRole('employee');
    }

    public function test_can_instantiate()
    {
        $this->assertInstanceOf(DeleteUserController::class, $this->controller);
    }

    public function test_delete_user()
    {
        $userId = $this->user->id;
        $request = new Request;
        $request->setUserResolver(fn () => $this->user);

        $this->userDeleteService->shouldReceive('execute')->once()->with($userId);

        // Invocar el controlador
        $response = $this->controller->__invoke($request);

        // Verificar que la respuesta sea un JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Decodificar el contenido JSON de la respuesta
        $responseData = $response->getData();
        $this->assertEquals('User deleted successfully', $responseData->message);
    }
}
