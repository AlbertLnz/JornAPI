<?php 
namespace Tests\Unit\Service\Dashboard;

use App\Enums\WorkTypeEnum;
use App\Exceptions\SalaryNotFoundException;
use App\Models\Employee;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Models\Salary;
use App\Models\User;
use App\Services\Dashboard\DashboardService;
use App\Services\HourSession\CurrentMonthHourSessionService;
use App\Services\Salary\FindSalaryByMonthService;
use App\Traits\TimeConverterTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    private DashboardService $dashboardService;
    use DatabaseTransactions;
    private HourWorked $hourWorked;
    private Salary $salary;
    private HourSession $hourSession;
    private FindSalaryByMonthService $findSalaryByMonthService;
    private Employee $employee;
    private CurrentMonthHourSessionService $currentMonthHourSessionService;

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
                    $this->findSalaryByMonthService = new FindSalaryByMonthService();
                    $this->currentMonthHourSessionService = new CurrentMonthHourSessionService();
                    $this->dashboardService = new DashboardService($this->findSalaryByMonthService,$this->currentMonthHourSessionService);
                
    }

    public function testCantInstantiate(): void
    {
        $this->assertInstanceOf(DashboardService::class, $this->dashboardService);
    }

    public function testGetCurrentMonth(): void
    {
        $user = $this->employee->user;
        $result = $this->dashboardService->execute($user);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_hours_worked', $result);
        $this->assertArrayHasKey('current_month_salary', $result);
    }

    public function testGetCurrentMonthNoSalary(): void
    {
        $this->salary->delete();
        $user = $this->employee->user;
       
       $result= $this->dashboardService->execute($user);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_hours_worked', $result);
        $this->assertArrayHasKey('current_month_salary', $result);
    }
}