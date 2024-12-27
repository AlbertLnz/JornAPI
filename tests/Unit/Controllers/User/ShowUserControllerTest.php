<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\User;

use App\DTO\User\UserDTO;
use App\Http\Controllers\v1\User\ShowUserController;
use App\Models\User;
use App\Services\User\FindUserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ShowUserControllerTest extends TestCase
{
    use DatabaseTransactions;

    private ShowUserController $controller;

    private MockInterface $findUserService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock del servicio FindUserService
        $this->findUserService = Mockery::mock(FindUserService::class);

        // Crear instancia del controlador con el mock
        $this->controller = new ShowUserController($this->findUserService);
    }

    public function test_show_user(): void
    {
        // Crear un usuario usando el factory
        $user = User::factory()->create([

        ]);

        // Mock del Request
        $mockedRequest = Mockery::mock(Request::class)->makePartial();
        $mockedRequest->shouldReceive('user')->andReturn($user);

        // Invocar el controlador
        $response = $this->controller->__invoke($mockedRequest);

        // Validar la respuesta
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = $response->getData(true);

        $this->assertEquals('User found successfully', $responseData['message']);
        $this->assertEquals(
            UserDTO::toArray($user->toArray()),
            $responseData['user']
        );
        $this->assertEquals(200, $response->getStatusCode());
    }
}
