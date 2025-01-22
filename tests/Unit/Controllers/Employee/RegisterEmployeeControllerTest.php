<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\Employee;

use App\DTO\Employee\RegisterEmployeeDTO;
use App\Exceptions\NullDataException;
use App\Exceptions\UserAlReadyExists;
use App\Http\Controllers\v1\Employee\RegisterEmployeeController;
use App\Http\Requests\RegisterEmployeeRequest;
use App\Services\Employee\RegisterEmployeeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class RegisterEmployeeControllerTest extends TestCase
{
    use DatabaseTransactions;

    private RegisterEmployeeController $controller;

    private RegisterEmployeeService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = Mockery::mock(RegisterEmployeeService::class);
        $this->controller = new RegisterEmployeeController($this->service);
    }

    public function test_can_instantiate(): void
    {
        $this->assertInstanceOf(RegisterEmployeeController::class, $this->controller);
    }

    public function test_register_employee_successfully(): void
    {
        // Arrange
        $request = new RegisterEmployeeRequest([
            'name' => 'Paul',
            'email' => 'paul@example.com',
            'company_name' => 'company',
            'password' => 'securepassword',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,
            'holiday_hourly_rate' => 20.0,
            'irpf' => 5.0,
        ]);

        $data = RegisterEmployeeDTO::toArray($request->all());

        $this->service->shouldReceive('execute')
            ->once()
            ->with($data);

        // Act
        $response = $this->controller->__invoke($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Employee created successfully', $response->getData()->message);
    }

    public function test_register_employee_throws_user_already_exists_exception(): void
    {
        // Arrange
        $request = new RegisterEmployeeRequest([
            'name' => 'Paul',
            'email' => 'paul@example.com',
            'company_name' => 'company',
            'password' => 'securepassword',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,
            'holiday_hourly_rate' => 20.0,
            'irpf' => 5.0,
        ]);

        $data = RegisterEmployeeDTO::toArray($request->all());

        $this->service->shouldReceive('execute')
            ->once()
            ->with($data)
            ->andThrow(new UserAlReadyExists());

        $this->expectException(HttpResponseException::class);

        // Act
        $this->controller->__invoke($request);
    }

    public function test_register_employee_throws_null_data_exception(): void
    {
        // Arrange
        $request = new RegisterEmployeeRequest([
            'name' => null,
            'email' => null,
            'company_name' => null,
            'password' => null,
            'normal_hourly_rate' => null,
            'overtime_hourly_rate' => null,
            'holiday_hourly_rate' => null,
            'irpf' => null,
        ]);

        $data = RegisterEmployeeDTO::toArray($request->all());

        $this->service->shouldReceive('execute')
            ->once()
            ->with($data)
            ->andThrow(new NullDataException('Null data provided', 422));

        $this->expectException(HttpResponseException::class);

        // Act
        $this->controller->__invoke($request);
    }
}
