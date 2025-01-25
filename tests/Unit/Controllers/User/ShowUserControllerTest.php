<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\User;

use App\DTO\User\UserDTO;
use App\Http\Controllers\v1\User\ShowUserController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ShowUserControllerTest extends TestCase
{
    private ShowUserController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        // Instancia del controlador
        $this->controller = new ShowUserController();
    }

    public function test_show_user(): void
    {
        // Crear un usuario usando el factory
        $user = User::factory()->create();

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
