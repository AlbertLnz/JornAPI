<?php

namespace Tests\Unit\Controllers\HourSession;

use App\Http\Controllers\v1\HourSession\RegisterHourSession\RegitsterHourSessionController;
use App\Http\Requests\RegisterHourSessionRequest;
use App\Models\Employee;
use App\Services\HourSession\RegisterHourSessionService;
use App\Services\HourWorked\HourWorkedEntryService;
use App\Services\Salary\SalaryService;
use Database\Factories\EmployeeFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class HourSessionRegisterControllerTest extends TestCase
{
    use DatabaseTransactions;

    private Employee $employee;

    private RegitsterHourSessionController $controller;

    private RegisterHourSessionService $service;

    private HourWorkedEntryService $hourWorkedEntryService;

    private SalaryService $salaryService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->employee = EmployeeFactory::new()->create([
            'name' => 'John Doe',
            'normal_hourly_rate' => 10.0,
            'overtime_hourly_rate' => 15.0,
            'holiday_hourly_rate' => 20.0,
        ]);
        $this->hourWorkedEntryService = new HourWorkedEntryService;
        $this->salaryService = new SalaryService;
        $this->service = new RegisterHourSessionService($this->hourWorkedEntryService, $this->salaryService);
        $this->controller = new RegitsterHourSessionController($this->service);
    }

    public function test_cant_instantiate()
    {
        $this->assertInstanceOf(RegitsterHourSessionController::class, $this->controller);
    }

    public function test_register_hour_session(): void
    {
        $user = $this->employee->user;
        $request = new RegisterHourSessionRequest(['date' => '2024-01-01', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'planned_hours' => 8, 'work_type' => 'is_holiday']);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $hourSession = $this->controller->__invoke($request);

        $this->assertEquals(201, $hourSession->status());
        $this->assertInstanceOf(JsonResponse::class, $hourSession);
        $this->assertDatabaseHas('hour_sessions', ['date' => '2024-01-01', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'planned_hours' => '8', 'work_type' => 'is_holiday']);

    }
}
