<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\User;

use App\Models\User;
use App\Http\Controllers\v1\User\ChangePasswordController;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\User\ChangePasswordService;
use App\Exceptions\ChangePasswordException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class ChangePasswordControllerTest extends TestCase
{
    use DatabaseTransactions;

    private ChangePasswordController $controller;

    private ChangePasswordService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = Mockery::mock(ChangePasswordService::class);
        $this->controller = new ChangePasswordController($this->service);
    }

    public function test_can_instantiate(): void
    {
        $this->assertInstanceOf(ChangePasswordController::class, $this->controller);
    }

    public function test_change_password_successfully(): void
    {
        // Arrange
        $user = User::factory()->create(); // Crea un usuario de prueba

        $request = new ChangePasswordRequest([
            'old_password' => 'oldpassword123',
            'new_password' => 'newpassword123',
        ]);

        // Mockeamos el método user() del request para devolver el usuario creado
        $request->setUserResolver(fn() => $user);

        $this->service->shouldReceive('execute')
            ->once()
            ->with($user, $request->old_password, $request->new_password);

        // Act
        $response = $this->controller->__invoke($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Password changed successfully', $response->getData()->message);
    }

    public function test_change_password_throws_change_password_exception(): void
    {
        // Arrange
        $this->expectException(HttpResponseException::class);

        $user = User::factory()->create(); // Crea un usuario de prueba

        $request = new ChangePasswordRequest([
            'old_password' => 'wrongoldpassword',
            'new_password' => 'newpassword123',
        ]);

        // Mockeamos el método user() del request para devolver el usuario creado
        $request->setUserResolver(fn() => $user);

        $this->service->shouldReceive('execute')
            ->once()
            ->with($user, $request->old_password, $request->new_password)
            ->andThrow(new ChangePasswordException('Invalid old password', 400));


        // Act
        $this->controller->__invoke($request);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
