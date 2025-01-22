<?php

namespace Tests\Unit\Service\HourSession;

use App\DTO\HourSession\HourSessionDTO;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\Employee;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Services\HourSession\FindHourSessionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ShowHourSessionServiceTest extends TestCase
{
    use DatabaseTransactions;

    private FindHourSessionService $hourSessionShowService;

    private HourSession $hourSession;

    private Employee $employee;

    private HourWorked $hourWorked;

    private string $randomDate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hourSessionShowService = new FindHourSessionService;
        $this->employee = Employee::factory()->create();
        $this->randomDate = date('Y-m-d', mt_rand(0, time()));

        $this->hourSession = HourSession::factory()->create([
            'employee_id' => $this->employee->id,
            'date' => $this->randomDate,
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
        ]);

    }

    public function test_cant_instantiate(): void
    {
        $this->assertInstanceOf(FindHourSessionService::class, $this->hourSessionShowService);
    }

    public function test_show_hour_session(): void
    {
        $findHourSession = $this->hourSessionShowService->execute($this->employee->id, $this->randomDate);
        $this->assertInstanceOf(HourSessionDTO::class, $findHourSession);
    }

    public function test_hour_session_not_found(): void
    {
        $this->expectException(HourSessionNotFoundException::class);

        $hourSession = $this->hourSessionShowService->execute($this->employee->id, '2023-01-01');
    }
}
