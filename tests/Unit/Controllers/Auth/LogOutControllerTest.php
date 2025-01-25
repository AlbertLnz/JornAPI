<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\Auth;

use App\Exceptions\InvalidTokenException;
use App\Http\Controllers\v1\Auth\LogOutController;
use App\Models\User;
use App\Services\Auth\LogOutService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class LogOutControllerTest extends TestCase
{
    use DatabaseTransactions;

    private LogOutController $controller;

    private LogOutService $logOutService;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logOutService = Mockery::mock(LogOutService::class);
        $this->controller = new LogOutController($this->logOutService);
        $this->user = User::factory()->create();
    }

    public function test_can_instantiate(): void
    {
        $this->assertInstanceOf(LogOutController::class, $this->controller);
    }

    public function test_log_out_successfully(): void
    {
        $token = 'mocked-valid-token';

        // Simular la lógica del servicio
        $this->logOutService
            ->shouldReceive('logOut')
            ->once()
            ->with($token)
            ->andReturnTrue();

        // Crear un request con el token
        $request = new Request;
        $request->headers->set('Authorization', 'Bearer '.$token);

        // Invocar el controlador
        $response = $this->controller->__invoke($request);

        // Verificar que la respuesta sea correcta
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Logged out successfully'], $response->getData(true));
    }

    public function test_log_out_with_invalid_token(): void
    {
        $token = 'mocked-invalid-token';

        // Simular que el servicio lanza una excepción de token inválido
        $this->logOutService
            ->shouldReceive('logOut')
            ->once()
            ->with($token)
            ->andThrow(new InvalidTokenException);

        // Crear un request con el token
        $request = new Request;
        $request->headers->set('Authorization', 'Bearer '.$token);

        $this->expectException(HttpResponseException::class);

        try {
            $this->controller->__invoke($request);
        } catch (HttpResponseException $e) {
            $response = $e->getResponse();
            $this->assertInstanceOf(JsonResponse::class, $response);
            $this->assertEquals(401, $response->getStatusCode());
            $this->assertEquals(['message' => 'Invalid token'], $response->getData(true));
            throw $e;
        }
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
