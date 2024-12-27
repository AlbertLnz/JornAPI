<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\Auth;

use App\Http\Controllers\v1\Auth\LoginController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;
use InvalidArgumentException;
use Mockery;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions;

    private LoginController $controller;

    private User $user;

    private TokenService $tokenService;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenService = Mockery::mock(TokenService::class);
        $this->authService = Mockery::mock(AuthService::class);
        $this->controller = new LoginController($this->authService);
        $this->user = User::factory()->create();
    }

    public function test_can_instantiate()
    {
        $this->assertInstanceOf(LoginController::class, $this->controller);
    }

    public function test_login()
    {
        $email = $this->user->email;
        $password = 'password';
        $request = new LoginRequest;
        $request->email = $email;
        $request->password = $password;
        $data = ['token' => 'mocked-jwt-token',
            'refreshToken' => 'mocked-refresh-token'];
        $this->authService->shouldReceive('execute')
            ->once()
            ->with($email, $password)
            ->andReturn($data);

        $response = $this->controller->__invoke($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = $response->getData();
        $this->assertEquals('mocked-jwt-token', $responseData->token);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_login_with_invalid_credentials()
    {
        $email = $this->user->email;
        $password = 'password';
        $request = new LoginRequest;
        $request->email = $email;
        $request->password = $password;
        $this->expectException(InvalidArgumentException::class);

        // Simulando que el servicio de autenticación lanza una excepción de no autorizado
        $this->authService->shouldReceive('execute')
            ->once()
            ->with($email, $password)
            ->andThrow(UnauthorizedException::class);

        // Ejecutar el controlador
        $response = $this->controller->__invoke($request);

        // Verificar que la respuesta es una instancia de JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Verificar que la respuesta tiene el código de estado 401 (Unauthorized)
        $this->assertEquals(401, $response->getStatusCode());

        // Verificar el contenido de la respuesta
        $responseData = $response->getData();
        $this->assertEquals('Unauthorized', $responseData->message);
    }
}
