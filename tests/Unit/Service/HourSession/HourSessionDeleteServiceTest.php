<?php

namespace Tests\Unit\Services\HourSession;

use App\Enums\WorkTypeEnum;
use App\Events\HourSessionUpdatedEvent;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\Employee;
use App\Models\HourSession;
use App\Services\HourSession\DeleteHourSessionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class HourSessionDeleteServiceTest extends TestCase
{
    use DatabaseTransactions;

    private HourSession $hourSession;

    private Employee $employee;

    private DeleteHourSessionService $service;

    /**
     * Set up the test environment and mocks.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->employee = Employee::factory()->create();
        $this->hourSession = HourSession::create([
            'employee_id' => $this->employee->id,
            'date' => '2024-10-13',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::NORMAL->value,
        ]);
        $this->service = new DeleteHourSessionService;
        // Limpiar mocks antes de cada test
    }

    public function test_cant_instantiate(): void
    {
        $this->assertInstanceOf(DeleteHourSessionService::class, $this->service);
    }

    /**
     * Test that an exception is thrown when the hour session does not exist.
     *
     * @return void
     */
    public function test_execute_throws_exception_if_hour_session_not_found()
    {
        $this->expectException(HourSessionNotFoundException::class);

        // Crear un empleado mediante la factory
        $this->service->execute($this->employee->id, '2024-12-14');

        // Verificar que se lanza una excepción cuando la sesión de trabajo no se encuentra

    }

    /**
     * Test that it deletes the hour session successfully.
     *
     * @return void
     */
    public function test_execute_deletes_hour_session_when_found()
    {
        Event::fake(HourSessionUpdatedEvent::class);

        $this->service->execute($this->employee->id, '2024-10-13');

        // Verificar que la sesión de trabajo se haya eliminado correctamente
        $this->assertNull(HourSession::find($this->hourSession->id));

        Event::assertDispatched(HourSessionUpdatedEvent::class);
    }
}
