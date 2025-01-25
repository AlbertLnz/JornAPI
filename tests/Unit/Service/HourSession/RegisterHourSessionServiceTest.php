<?php

declare(strict_types=1);

namespace Tests\Unit\Services\HourSession;

use App\DTO\HourSession\HourSessionDTO;
use App\Enums\WorkTypeEnum;
use App\Events\RegisteredHourSessionEvent;
use App\Exceptions\HourSessionExistException;
use App\Models\HourSession;
use App\Services\HourSession\RegisterHourSessionService;
use App\Services\HourWorked\HourWorkedEntryService;
use App\Services\Salary\SalaryService;
use Database\Factories\EmployeeFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;

class RegisterHourSessionServiceTest extends TestCase
{
    use DatabaseTransactions;

    private HourWorkedEntryService $hourWorkedEntryServiceMock;

    private SalaryService $salaryServiceMock;

    protected function setUp(): void
    {
        parent::setUp();
        // Prepara mocks para HourWorkedEntryService y SalaryService
        $this->hourWorkedEntryServiceMock = Mockery::mock(HourWorkedEntryService::class);
    }

    public function test_execute_creates_hour_session_and_calls_services(): void
    {
        Event::fake();
        $employee = EmployeeFactory::new()->create();
        $employeeId = $employee->id;
        $date = '2024-02-13';
        $startTime = '09:00';
        $endTime = '17:00';
        $plannedHours = 8;
        $workType = WorkTypeEnum::NORMAL->value;

        $data = [
            'employee_id' => $employeeId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'planned_hours' => $plannedHours,
            'work_type' => $workType,
        ];
    

        // Crear la instancia del servicio bajo prueba
        $service = new RegisterHourSessionService(
            $this->hourWorkedEntryServiceMock,
        );

        $hourSessionDTO = new HourSessionDTO($date, $startTime, $endTime, $plannedHours, $workType);
        // Llamar al método 'execute'
        $service->execute($employeeId, $hourSessionDTO);

        // Verificar que la sesión de horas fue creada en la base de datos
        $this->assertDatabaseHas('hour_sessions', [
            'employee_id' => $employeeId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'planned_hours' => $plannedHours,
            'work_type' => $workType,
        ]);
        Event::assertDispatched(RegisteredHourSessionEvent::class, function ($event) use ($employee, $data) {
            return $event->getEmployeeId() === $employee->id && $event->getDate() === $data['date'];
        });
    }

    public function test_execute_throws_exception_when_hour_session_exists(): void
    {
        $employee = EmployeeFactory::new()->create();

        $employeeId = $employee->id;
        $date = '2024-04-13';
        $startTime = '09:00';
        $endTime = '17:00';
        $plannedHours = 8;
        $workType = WorkTypeEnum::NORMAL->value;

        // Crear una sesión de horas previamente para simular la existencia
        HourSession::create([
            'employee_id' => $employeeId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'planned_hours' => $plannedHours,
            'work_type' => $workType,
        ]);

        // Crear la instancia del servicio bajo prueba
        $service = new RegisterHourSessionService(
            $this->hourWorkedEntryServiceMock,
        );
        $hourSessionDTO = new HourSessionDTO($date, $startTime, $endTime, $plannedHours, $workType);


        // Verificar que se lanza una excepción cuando la sesión ya existe
        $this->expectException(HourSessionExistException::class);
        $service->execute($employeeId, $hourSessionDTO);
    }
}
