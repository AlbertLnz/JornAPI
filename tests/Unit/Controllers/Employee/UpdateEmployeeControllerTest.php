<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\Employee;

use App\Exceptions\UserNotFound;
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

    public function setUp(): void
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

    public function testEmployeeUpdateSuccess(): void
    {
        // Mockear el request y asociarlo al usuario
        $request = Mockery::mock(UpdateEmployeeRequest::class);
        $request->shouldReceive('user')->andReturn($this->user);
        $request->name = 'John Doe';
        $request->company_name = 'Acme Inc.';
        $request->normal_hourly_rate = 10.0;
        $request->overtime_hourly_rate = 15.0;
        $request->holiday_hourly_rate = 25.0;
        $request->irpf = 30.0;

        // Configurar el servicio simulado
        $this->employeeUpdateService->shouldReceive('execute')
            ->once()
            ->with(
                'John Doe',
                'Acme Inc.',
                10.0,
                15.0,
                25.0,
                30.0,
                $this->user->id
            )
            ->andReturn([
                'id' => $this->employee->id,
                'name' => 'John Doe',
                'company' => 'Acme Inc.',
                'normal_hourly_rate' => 10.0,
                'overtime_hourly_rate' => 15.0,
                'holiday_hourly_rate' => 25.0,
                'irpf' => 30.0,
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
                'name' => 'John Doe',
                'company' => 'Acme Inc.',
                'normal_hourly_rate' => 10.0,
                'overtime_hourly_rate' => 15.0,
                'holiday_hourly_rate' => 25.0,
                'irpf' => 30.0,
            ],
        ], $response->getData(true));
    }

   
     public function testUpdateEmployeeWithNullData(): void
    {
        $request = Mockery::mock(UpdateEmployeeRequest::class);
        $request->shouldReceive('user')->andReturn($this->user);
        $request->name = null;
        $request->company_name = null;
        $request->normal_hourly_rate = null;
        $request->overtime_hourly_rate = null;
        $request->holiday_hourly_rate = null;
        $request->irpf = null;

        $this->employeeUpdateService->shouldReceive('execute')
            ->once()
            ->with(
                null,
                null,
                null,
                null,
                null,
                null,
                $this->user->id
            )
            ->andReturn([
                'id' => $this->employee->id,
                'name' => 'John Doe',
                'company' => 'Acme Inc.',
                'normal_hourly_rate' => 10.0,
                'overtime_hourly_rate' => 15.0,
                'holiday_hourly_rate' => 25.0,
                'irpf' => 30.0,
            ]);

       

      $response=  $this->controller->__invoke($request);

      $this->assertInstanceOf(JsonResponse::class, $response);
      $this->assertEquals(200, $response->status());
      $this->assertEquals([
        'message' => 'Employee updated successfully',
        'employee' => [
            'id' => $this->employee->id,
            'name' => 'John Doe',
            'company' => 'Acme Inc.',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,
            'holiday_hourly_rate' => 25.0,
            'irpf' => 30.0,
        ],
    ], $response->getData(true));

      
            
    } 

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
