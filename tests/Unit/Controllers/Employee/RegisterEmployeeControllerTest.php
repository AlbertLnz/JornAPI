<?php 

declare(strict_types=1);
namespace Tests\Unit\Controllers\Employee;

use App\Http\Controllers\v1\Employee\RegisterEmployeeController;
use App\Http\Requests\RegisterEmployeeRequest;
use App\Exceptions\UserAlreadyExists;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->service = Mockery::mock(RegisterEmployeeService::class);
        $this->controller = new RegisterEmployeeController($this->service);
    }

    public function testCanInstantiate(): void
    {
        $this->assertInstanceOf(RegisterEmployeeController::class, $this->controller);
    }

    public function testRegisterEmployeeSuccessfully(): void
    {
        // Arrange
        $request = new RegisterEmployeeRequest([
            'name' => 'Paul',
            'email' => 'paul@example.com',
            'password' => 'securepassword',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,
            'holiday_hourly_rate' => 20.0,
            'irpf' => 5.0
        ]);

        $this->service->shouldReceive('execute')
            ->once()
            ->with(
                $request->name,
                $request->email,
                $request->password,
                $request->normal_hourly_rate,
                $request->overtime_hourly_rate,
                $request->holiday_hourly_rate,
                $request->irpf
            );

        // Act
        $response = $this->controller->__invoke($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Employee created successfully', $response->getData()->message);
    }

    public function testRegisterEmployeeThrowsUserAlreadyExistsException(): void
    {
        // Arrange
        $request = new RegisterEmployeeRequest([
            'name' => 'Paul',
            'email' => 'paul@example.com',
            'password' => 'securepassword',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,
            'holiday_hourly_rate' => 20.0,
            'irpf' => 5.0
        ]);

        $this->service->shouldReceive('execute')
            ->once()
            ->with(
                $request->name,
                $request->email,
                $request->password,
                $request->normal_hourly_rate,
                $request->overtime_hourly_rate,
                $request->holiday_hourly_rate,
                $request->irpf
            )
            ->andThrow(new UserAlreadyExists());

        $this->expectException(HttpResponseException::class);


        // Act
       $response= $this->controller->__invoke($request);
       $this->assertEquals(409, $response->getStatusCode());
    }

}
