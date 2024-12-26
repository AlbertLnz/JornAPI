<?php 
namespace Tests\Unit\Service\Salary;

use App\Enums\WorkTypeEnum;
use App\Exceptions\SalaryNotFoundException;
use App\Models\Employee;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Models\Salary;
use App\Services\Salary\FindSalaryByMonthService;
use App\Services\Salary\SalaryService;
use Database\Factories\HourWorkedFactory;
use Database\Factories\SalaryFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FindSalaryByMonthServiceTest extends TestCase
{
    use DatabaseTransactions;
    private HourWorked $hourWorked;
    private Salary $salary;
    private SalaryService $salaryService;
    private HourSession $hourSession;
    private FindSalaryByMonthService $findSalaryByMonthService;
    private Employee $employee;

    protected function setUp(): void{
        parent::setUp();
        $this->employee = Employee::factory()->create(['normal_hourly_rate' => 10]);
        $this->hourSession = HourSession::factory()->create(['date' => '2022-12-21',
            'employee_id' => $this->employee->id,
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::NORMAL->value]);
            $this->hourWorked = HourWorked::factory()->create(['hour_session_id' => $this->hourSession->id,
                'normal_hours' => 8,
                'overtime_hours' => 0,
                'holiday_hours' => 0]);
                $this->salary = Salary::create(['employee_id' => $this->employee->id,
                    'start_date' => '2022-12-01',
                    'end_date' => '2022-12-31',
                    'total_normal_hours' => 8,
                    'total_overtime_hours' => 0,
                    'total_holiday_hours' => 0,
                    'total_gross_salary' =>80.00,
                    'total_net_salary' => 0]);
                    $this->salaryService = new SalaryService();
                    $this->findSalaryByMonthService = new FindSalaryByMonthService();
                
    }

    public function cantInstantiate(){
        $this->assertInstanceOf(FindSalaryByMonthService::class, $this->findSalaryByMonthService);
    }

    public function test_find_salary_by_month_service_execute(){
        $this->findSalaryByMonthService->execute($this->employee->id, '12', '2022');
        $this->assertDatabaseHas('salaries', ['employee_id' => $this->employee->id,
            'total_normal_hours' => 8,
            'total_overtime_hours' => 0,
            'total_holiday_hours' => 0,
            'total_gross_salary' => 80.00,
            'total_net_salary' => 0]);
    }

    public function test_find_salary_by_month_service_execute_throws_exception(){
        $this->expectException(SalaryNotFoundException::class);
        $this->findSalaryByMonthService->execute($this->employee->id, '05', '2021');
    }

    public function test_find_salary_by_month_with_null_values(){
        $this->expectException(SalaryNotFoundException::class);
        $this->assertNull($this->findSalaryByMonthService->execute($this->employee->id, null, null));
    }
}