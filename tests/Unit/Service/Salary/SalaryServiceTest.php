<?php

namespace Tests\Unit\Service\Salary;

use App\Enums\WorkTypeEnum;
use App\Models\Employee;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Services\Salary\SalaryService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SalaryServiceTest extends TestCase
{
    use DatabaseTransactions;

    private SalaryService $salaryService;

    private Employee $employee;

    private HourWorked $hourWorked;

    private HourSession $hourSession;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employee = Employee::factory()->create();
        $this->hourSession = HourSession::factory()->create(['employee_id' => $this->employee->id,
            'date' => '2023-01-19',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::NORMAL->value]);
        $this->hourWorked = HourWorked::factory()->create(['hour_session_id' => $this->hourSession->id,
            'normal_hours' => 8,
            'overtime_hours' => 0,
            'holiday_hours' => 0]);
        $this->salaryService = new SalaryService;
    }

    public function test_cant_instantiate(): void
    {
        $this->assertInstanceOf(SalaryService::class, $this->salaryService);
    }

    public function test_salary_service_execute()
    {
        $this->salaryService->execute($this->employee->id, '2023-01-19');
        $this->assertDatabaseHas('salaries', [
            'employee_id' => $this->employee->id,
            'total_normal_hours' => 8,
            
        ]);
    }
}
