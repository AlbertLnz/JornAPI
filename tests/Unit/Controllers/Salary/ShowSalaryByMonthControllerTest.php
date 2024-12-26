<?php 
namespace Tests\Unit\Controllers\Salary;

use Tests\TestCase;
use App\Http\Controllers\v1\Salary\ShowSalaryByMonthController;
use App\Http\Requests\SalaryByMonthRequest;
use App\Services\Salary\FindSalaryByMonthService;
use App\Models\Employee;
use App\Models\Salary;
use App\Exceptions\SalaryNotFoundException;
use App\DTO\Salary\SalaryDTO;
use App\Enums\WorkTypeEnum;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Services\Salary\SalaryService;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ShowSalaryByMonthControllerTest extends TestCase{
    use DatabaseTransactions;
    private HourWorked $hourWorked;
    private Salary $salary;
    private SalaryService $salaryService;
    private HourSession $hourSession;
    private FindSalaryByMonthService $findSalaryByMonthService;
    private Employee $employee;
    private ShowSalaryByMonthController $showSalaryByMonthController;

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
                    $this->showSalaryByMonthController = new ShowSalaryByMonthController($this->findSalaryByMonthService);

                
    }

    public function testCantInstantiate(){
        $this->assertInstanceOf(ShowSalaryByMonthController::class, $this->showSalaryByMonthController);
    }

    public function testShowSalaryByMonth(){
        $user = $this->employee->user;

        // Simular que el usuario está autenticado
        $this->actingAs($user);
        $request = new SalaryByMonthRequest(['month' => '12', 'year' => '2022']);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $salary = $this->showSalaryByMonthController->__invoke($request, );
        $this->assertEquals(200, $salary->status());
        $this->assertInstanceOf(JsonResponse::class, $salary);
    }

    public function testShowSalaryByMonthNotFound(){
        $this->expectException(HttpResponseException::class);

        $user = $this->employee->user;
        // Simular que el usuario está autenticado
        $this->actingAs($user);
        $request = new SalaryByMonthRequest(['month' => '12', 'year' => '2023']);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        $this->showSalaryByMonthController->__invoke($request);
    }

 
}