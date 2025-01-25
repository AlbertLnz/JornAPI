<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\Employee;

use App\Http\Controllers\v1\Employee\UpdateEmployeeController;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use App\Services\Employee\UpdateEmployeeService;
use Illuminate\Http\JsonResponse;
use Mockery;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class UpdateEmployeeControllerTest extends TestCase
{
    private UpdateEmployeeService $employeeUpdateService;

    private UpdateEmployeeController $controller;

    private User $user;

    private Employee $employee;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear mocks y objetos necesarios
        $this->employeeUpdateService = Mockery::mock(UpdateEmployeeService::class);
        $this->controller = new UpdateEmployeeController($this->employeeUpdateService);

        // Simular usuario con UUID
        $this->user = User::factory()->create([
            'id' => Uuid::uuid4()->toString(),
        ]);

        // Simular empleado
        $this->employee = Employee::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    public function test_employee_update_success(): void
    {
        // Datos de la solicitud
        $requestData = [
            'name' => 'John Doe',
            'company_name' => 'Acme Inc.',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,
            'holiday_hourly_rate' => 25.0,
            'irpf' => 30.0,
        ];

        // Mockear el request y definir sus métodos
        $request = Mockery::mock(UpdateEmployeeRequest::class);
        $request->shouldReceive('user')->andReturn($this->user);
        $request->shouldReceive('all')->andReturn($requestData); // Simular el método all()

        // Configurar el servicio simulado
        $this->employeeUpdateService->shouldReceive('execute')
            ->once()
            ->with(
                $requestData['name'],
                $requestData['company_name'],
                $requestData['normal_hourly_rate'],
                $requestData['overtime_hourly_rate'],
                $requestData['holiday_hourly_rate'],
                $requestData['irpf'],
                $this->user->employee
            )
            ->andReturn([
                'id' => $this->employee->id,
                'name' => $requestData['name'],
                'company' => $requestData['company_name'],
                'normal_hourly_rate' => $requestData['normal_hourly_rate'],
                'overtime_hourly_rate' => $requestData['overtime_hourly_rate'],
                'holiday_hourly_rate' => $requestData['holiday_hourly_rate'],
                'irpf' => $requestData['irpf'],
            ]);

        // Llamar al método del controlador
        $response = $this->controller->__invoke($request);

        // Verificar la respuesta
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals([
            'message' => 'Employee updated successfully',
            'employee' => [
                'id' => $this->employee->id,
                'name' => $requestData['name'],
                'company' => $requestData['company_name'],
                'normal_hourly_rate' => $requestData['normal_hourly_rate'],
                'overtime_hourly_rate' => $requestData['overtime_hourly_rate'],
                'holiday_hourly_rate' => $requestData['holiday_hourly_rate'],
                'irpf' => $requestData['irpf'],
            ],
        ], $response->getData(true));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
