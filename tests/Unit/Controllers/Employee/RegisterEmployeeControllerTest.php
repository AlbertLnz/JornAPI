<?php 
declare(strict_types=1);
namespace Tests\Unit\Controllers\Employee;

use App\Http\Controllers\v1\Employee\RegisterEmployeeController;
use App\Http\Requests\RegisterEmployeeRequest;
use App\Models\User;
use App\Services\Employee\RegisterEmployeeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class RegisterEmployeeControllerTest extends TestCase{
    use DatabaseTransactions;
    private RegisterEmployeeController $controller;
    private User $user;
    private RegisterEmployeeService $service;
    public function setUp(): void
    {
        parent::setUp();
        $this->service = Mockery::mock(RegisterEmployeeService::class);
        $this->controller = new RegisterEmployeeController($this->service);
        $this->user = User::factory()->create();
    }

    public function testCanInstantiate()
    {
        $this->assertInstanceOf(RegisterEmployeeController::class, $this->controller);
    }

    public function testRegisterEmployee()
    {
        $name = $this->user->name;
        $email = $this->user->email;
        $companyName = 'Company';
         $normalHourlyRate = 10.0;
        $overtimeHourlyRate = 15.0;
        $nightHourlyRate = 20.0;
        $holidayHourlyRate = 25.0;
        $password = 'password';
        $request = new RegisterEmployeeRequest();
        $request->name = 'Paul';
        $request->email = $email;
        $request->password = $password;
        $request->normal_hourly_rate = $normalHourlyRate;
        $request->overtime_hourly_rate = $overtimeHourlyRate;
        $request->night_hourly_rate = $nightHourlyRate;
        $request->irpf = 0.0;
        $request->holiday_hourly_rate = $holidayHourlyRate;
        $this->service->shouldReceive('execute')
            ->once()
            ->with($request->name, $request->email, $request->password, $request->normal_hourly_rate, $request->overtime_hourly_rate, $request->night_hourly_rate,$request->holiday_hourly_rate, $request->irpf);
        $response = $this->controller->__invoke($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = $response->getData();
        $this->assertEquals(201, $response->getStatusCode());
    }

}