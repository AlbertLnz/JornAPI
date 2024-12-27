<?php

namespace Tests\Unit\Service\HourWorked;

use App\Enums\WorkTypeEnum;
use App\Exceptions\TimeEntryException;
use App\Models\Employee;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Services\HourWorked\HourWorkedUpdateService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HourWorkedUpdateServiceTest extends TestCase
{
    use DatabaseTransactions;

    private HourSession $hourSession;

    private Employee $employee;

    private HourWorked $hourWorked;

    private HourWorkedUpdateService $hourWorkedUpdateService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hourSession = HourSession::factory()->create([
            'date' => '2023-01-21',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::NORMAL->value,
        ]);
        $this->hourWorked = HourWorked::create([
            'hour_session_id' => $this->hourSession->id,
            'normal_hours' => 8,
            'overtime_hours' => 0,
            'holiday_hours' => 0,
        ]);
        $this->hourWorkedUpdateService = new HourWorkedUpdateService;
    }

    public function test_cant_instantiate(): void
    {
        $this->assertInstanceOf(HourWorkedUpdateService::class, $this->hourWorkedUpdateService);
    }

    public function test_update_hour_worked(): void
    {
        $this->hourWorkedUpdateService->execute(
            $this->hourSession->id,
            $this->hourSession->start_time,
            $this->hourSession->end_time,
            $this->hourSession->planned_hours,
            WorkTypeEnum::OVERTIME->value
        );
        $this->assertDatabaseHas('hour_workeds', [
            'hour_session_id' => $this->hourSession->id,
            'normal_hours' => 0,
            'overtime_hours' => 8,
            'holiday_hours' => 0,
        ]);
    }

    public function test_update_hour_worked_holiday(): void
    {
        $this->hourWorkedUpdateService->execute(
            $this->hourSession->id,
            $this->hourSession->start_time,
            $this->hourSession->end_time,
            $this->hourSession->planned_hours,
            WorkTypeEnum::HOLIDAY->value
        );
        $this->assertDatabaseHas('hour_workeds', [
            'hour_session_id' => $this->hourSession->id,
            'normal_hours' => 0,
            'overtime_hours' => 0,
            'holiday_hours' => 8,
        ]);
    }

    public function test_update_hour_worked_with_null_start_time_and_end_time(): void
    {
        $this->expectException(TimeEntryException::class);
        $this->hourWorkedUpdateService->execute(
            $this->hourSession->id,
            null,
            null,
            8,
            WorkTypeEnum::NORMAL->value
        );

    }
}
