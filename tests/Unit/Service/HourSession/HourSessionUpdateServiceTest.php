<?php 
namespace Tests\Unit\Services\HourSession;

use App\Enums\WorkTypeEnum;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\Employee;
use App\Models\HourSession;
use App\Models\HourWorked;
use App\Services\HourSession\HourSessionUpdateService;
use App\Services\HourSession\UpdateHourSessionService;
use App\Services\HourWorked\HourWorkedUpdateService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;

class HourSessionUpdateServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testCantInstantiate(): void
    {
        $employee = Employee::factory()->create();
        $hourSessionUpdateService = new UpdateHourSessionService(new HourWorkedUpdateService());
        $this->assertInstanceOf(UpdateHourSessionService::class, $hourSessionUpdateService);
    }

    public function test_execute_throws_exception_if_hour_session_not_found(): void
    {
        $this->expectException(HourSessionNotFoundException::class);

        // Crear empleado
        $employee = Employee::factory()->create();
        
        $hourSessionUpdateService = new UpdateHourSessionService(new HourWorkedUpdateService());

        // Intentar actualizar una sesión de trabajo que no existe
        $hourSessionUpdateService->execute(
            $employee->id, 
            '2024-11-13', 
            '09:00', 
            '17:00', 
            8, 
            WorkTypeEnum::NORMAL->value
        );
    }

    public function test_execute_updates_hour_session(): void
    {
        // Crear empleado, sesión y hora trabajada
        $employee = Employee::factory()->create();
        $hourSession = HourSession::factory()->create([
            'employee_id' => $employee->id,
            'date' => '2024-07-13',
            'start_time' => '09:00',
            'end_time' => '17:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::NORMAL->value
        ]);
        $hourWorked = HourWorked::create([
            'hour_session_id' => $hourSession->id,
            'normal_hours' => 8,
            'overtime_hours' => 0,
            'holiday_hours' => 0
        ]);

        // Crear un mock de HourWorkedUpdateService
        $hourWorkedUpdateServiceMock = Mockery::mock(HourWorkedUpdateService::class);
        $hourWorkedUpdateServiceMock->shouldReceive('execute')
            ->once()
            ->with(
                $hourSession->id,
                '09:00',
                '16:00',
                8,
                WorkTypeEnum::HOLIDAY->value
            );

        // Crear el servicio HourSessionUpdateService con el mock de HourWorkedUpdateService
        $hourSessionUpdateService = new UpdateHourSessionService($hourWorkedUpdateServiceMock);

        // Ejecutar la actualización
        $result = $hourSessionUpdateService->execute(
            $employee->id, 
            $hourSession->date, 
            '09:00', 
            '16:00', 
            $hourSession->planned_hours, 
            WorkTypeEnum::HOLIDAY->value
        );

        // Verificar que la base de datos refleja los cambios en hour_sessions
        $this->assertDatabaseHas('hour_sessions', [
            'employee_id' => $hourSession->employee_id,
            'date' => $hourSession->date,
            'start_time' => '09:00',
            'end_time' => '16:00',
            'planned_hours' => 8,
            'work_type' => WorkTypeEnum::HOLIDAY->value // Verificamos el cambio en work_type en hour_sessions
        ]);

        // Verificar que la base de datos refleja los cambios en hour_workeds (aunque no es necesario que work_type esté en hour_workeds)
        $this->assertDatabaseHas('hour_workeds', [
            'hour_session_id' => $hourSession->id,
            'normal_hours' => 8, // Verifica que las horas trabajadas son correctas
            'overtime_hours' => 0,
            'holiday_hours' => 0
        ]);

        // Asegurarse de que se haya ejecutado la actualización de HourWorked
        Mockery::close();
    }
}
