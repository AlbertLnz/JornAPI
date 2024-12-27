<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Employee;

use App\Exceptions\UserNotFound;
use App\Models\Employee;
use App\Services\Employee\UpdateEmployeeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UpdateEmployeeServiceTest extends TestCase
{
    use DatabaseTransactions;

    private UpdateEmployeeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UpdateEmployeeService;
    }

    public function test_can_instantiate()
    {
        $this->assertInstanceOf(UpdateEmployeeService::class, $this->service);
    }

    public function test_update_employee_service_with_valid_data()
    {
        // Arrange: Crear un empleado ficticio
        $employee = Employee::factory()->create();

        // Act: Actualizar el empleado usando el servicio
        $updatedEmployeeData = $this->service->execute(
            'Peter',
            'Facebook',
            10.00,
            10.30,
            13.00,
            5.00,
            $employee->user_id
        );

        // Assert: Validar los cambios en la base de datos
        $this->assertEquals('Peter', $updatedEmployeeData['name']);
        $this->assertEquals('Facebook', $updatedEmployeeData['company_name']);
        $this->assertEquals(10.00, $updatedEmployeeData['normal_hourly_rate']);
        $this->assertEquals(10.30, $updatedEmployeeData['overtime_hourly_rate']);
        $this->assertEquals(13.00, $updatedEmployeeData['holiday_hourly_rate']);
        $this->assertEquals(5.00, $updatedEmployeeData['irpf']);
    }

    public function test_update_employee_service_throws_user_not_found_exception()
    {
        // Assert: Esperamos que se lance la excepción UserNotFound
        $this->expectException(UserNotFound::class);

        // Act: Ejecutar el servicio con un UUID inexistente
        $this->service->execute(
            'Peter',
            'Facebook',
            10.00,
            10.30,
            13.00,
            4.00,
            'non-existing-uuid'
        );
    }

    public function test_update_employee_service_with_null_data()
    {
        // Arrange: Crear un empleado ficticio
        $employee = Employee::factory()->create();

        // Act: Actualizar el empleado usando el servicio
        $updatedEmployeeData = $this->service->execute(
            null,
            null,
            null,
            null,
            null,
            null,
            $employee->user_id
        );

        // Assert: Validar que los datos no hayan cambiado
        $this->assertEquals($employee->name, $updatedEmployeeData['name']);
        $this->assertEquals($employee->company_name, $updatedEmployeeData['company_name']);
        $this->assertEquals($employee->normal_hourly_rate, $updatedEmployeeData['normal_hourly_rate']);
        $this->assertEquals($employee->overtime_hourly_rate, $updatedEmployeeData['overtime_hourly_rate']);
        $this->assertEquals($employee->holiday_hourly_rate, $updatedEmployeeData['holiday_hourly_rate']);
        $this->assertEquals($employee->irpf, $updatedEmployeeData['irpf']);
    }
}
