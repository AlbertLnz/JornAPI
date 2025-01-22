<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\Employee;

use App\DTO\Employee\ShowEmployeeDTO;
use App\Http\Controllers\v1\Employee\ShowEmployeeController;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class ShowEmployeeControllerTest extends TestCase
{
    use DatabaseTransactions;

    private ShowEmployeeController $controller;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = new ShowEmployeeController;

        // Crear usuario con relación a empleado
        $this->user = User::factory()->create();
        Employee::factory()->create(['user_id' => $this->user->id]);
    }
    public function test_can_instantiate()
    {
        $this->assertInstanceOf(ShowEmployeeController::class, $this->controller);
    }
    public function test_show_employee_returns_dto_instance()
    {
        $request = new Request;
        $request->setUserResolver(fn () => $this->user);

        $response = $this->controller->__invoke($request);

        // Verificar que el resultado sea un JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = $response->getData();

        $this->assertEquals('Employee found successfully', $responseData->message);

        $this->assertInstanceOf(ShowEmployeeDTO::class, ShowEmployeeDTO::fromModel($this->user->employee));

        // Verificar que los datos en el DTO coincidan con el modelo del empleado
        $employee = $this->user->employee;
        $this->assertEquals($employee->name, $responseData->employee->name);
        $this->assertEquals($employee->company_name, $responseData->employee->company_name);
        $this->assertEquals((string) $employee->irpf, $responseData->employee->irpf);
    }
}
