<?php

declare(strict_types=1);

namespace Tests\Unit\Controllers\Employee;

use App\DTO\Employee\EmployeeDTO;
use App\Http\Controllers\v1\Employee\ShowEmployeeController;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class ShowEmployeeControllerTest extends TestCase
{
    use DatabaseTransactions;

    private ShowEmployeeController $controller;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->controller = new ShowEmployeeController();

        // Crear usuario con relación a empleado
        $this->user = User::factory()->create();
        Employee::factory()->create(['user_id' => $this->user->id]);
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(ShowEmployeeController::class, $this->controller);
    }

    public function testShowEmployeeSuccess()
    {
        // Crear una solicitud con el usuario autenticado
        $request = new Request();
        $request->setUserResolver(fn () => $this->user);

        // Invocar el controlador
        $response = $this->controller->__invoke($request);

        // Verificar que el resultado sea un JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Decodificar el contenido JSON de la respuesta
        $responseData = $response->getData();

        $this->assertEquals('Employee found successfully', $responseData->message);

        // Verificar que los datos del empleado sean correctos
        $employee = $this->user->employee;
        $this->assertNotNull($responseData->employee);
        $this->assertEquals($employee->name, $responseData->employee->name);
    }

  
}
