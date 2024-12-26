<?php
namespace Tests\Unit\Service\HourSession;

use App\Enums\WorkTypeEnum;
use App\Models\Employee;
use App\Models\HourSession;
use App\Services\HourSession\CurrentMonthHourSessionService;
use App\Services\HourSession\HourSessionShowService;
use Carbon\Carbon;
use Database\Factories\HourSessionFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CurrentMonthHourSessionsServiceTest extends TestCase
{
    use DatabaseTransactions;
    private Employee $employee;
    private CurrentMonthHourSessionService $currentMonthHourSessionService;
    private HourSession $hourSession;

    public function setUp(): void
    {
        parent::setUp();
        $this->employee = Employee::factory()->create();
        $this->hourSession = HourSession::factory()->create(['employee_id' => $this->employee->id,
            'date' => '2023-12-01',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::NORMAL->value]);
        // Crear 10 empleados y persistirlos en la base de datos


        $this->currentMonthHourSessionService = new CurrentMonthHourSessionService();
    }

    public function testCantInstantiate(): void
    {
        $this->assertInstanceOf(CurrentMonthHourSessionService::class, $this->currentMonthHourSessionService);
    }

    public function test_show_hour_sessions(): void
    {
        $result = $this->currentMonthHourSessionService->execute($this->employee->id, Carbon::create('2023','12','1'), Carbon::create('2023-12-31'));
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
    }

    public function test_show_hour_sessions_empty(): void
    {
        $result = $this->currentMonthHourSessionService->execute($this->employee->id, Carbon::create('2022-12-01'), Carbon::create('2022-12-31'));
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(0, $result);
    }

   
}