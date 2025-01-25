<?php

namespace Tests\Unit\Services\HourSession;

use App\Enums\WorkTypeEnum;
use App\Events\UpdatedHourSessionEvent;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\Employee;
use App\Models\HourSession;
use App\Services\HourSession\UpdateHourSessionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UpdateHourSessionServiceTest extends TestCase
{
    use DatabaseTransactions;

    private UpdateHourSessionService $hourSessionUpdateService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hourSessionUpdateService = app(UpdateHourSessionService::class);
    }

    public function test_can_instantiate_service(): void
    {
        $this->assertInstanceOf(UpdateHourSessionService::class, $this->hourSessionUpdateService);
    }

    public function test_throws_exception_if_hour_session_not_found(): void
    {
        $this->expectException(HourSessionNotFoundException::class);

        $employee = Employee::factory()->create();
        $this->hourSessionUpdateService->execute(
            $employee->id,
            '2024-11-13',
            '09:00',
            '17:00',
            8,
            WorkTypeEnum::NORMAL->value
        );
    }

    public function test_updates_hour_session_and_dispatches_event(): void
    {
        Event::fake();

        $employee = Employee::factory()->create();
        $hourSession = HourSession::factory()->create([
            'employee_id' => $employee->id,
            'date' => '2024-07-13',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::NORMAL->value,
        ]);

        $updatedData = [
            'date' => '2024-07-13',
            'start_time' => '09:00',
            'end_time' => '16:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::HOLIDAY->value,
        ];

        $this->hourSessionUpdateService->execute(
            $employee->id,
            $updatedData['date'],
            $updatedData['start_time'],
            $updatedData['end_time'],
            $updatedData['planned_hours'],
            $updatedData['work_type']
        );

        // Verificar base de datos
        $this->assertDatabaseHas('hour_sessions', array_merge(
            ['employee_id' => $employee->id],
            $updatedData
        ));

        // Verificar evento disparado
        Event::assertDispatched(UpdatedHourSessionEvent::class, function ($event) use ($employee, $updatedData) {
            return $event->getEmployeeId() === $employee->id && $event->getDate() === $updatedData['date'];
        });
    }
}
