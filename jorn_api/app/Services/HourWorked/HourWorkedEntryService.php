<?php

namespace App\Services\HourWorked;

use App\Models\HourWorked;
use App\Services\Salary\SalaryService;
use Carbon\Carbon;
use App\Traits\ValidateTimeEntry;

class HourWorkedEntryService
{
    use ValidateTimeEntry;

    public function __construct(private SalaryService $salaryService) {}

    public function execute(string $hourSessionId, $startTime, $endTime, $plannedHours, $isHoliday, $isOvertime)
    {
        // Validar la entrada de tiempo
        $this->validateTimeEntry($startTime, $endTime);

        // Convertir los tiempos a objetos Carbon

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        // Si el tiempo de fin es menor o igual al inicio, agregamos un día (cruza medianoche)
        if ($end <= $start) {
            $end->addDay();
        }

        // Calcular las horas trabajadas
        $hoursWorkedCalculated = $start->floatDiffInHours($end);

        // Calcular horas extras
        $overtimeHours = $this->calculateOvertimeHours($hoursWorkedCalculated, $plannedHours);

        // Verificar si es festivo y calcular las horas festivas
        $holidayHours = $this->calculateHolidayHours($hoursWorkedCalculated, $overtimeHours, $isHoliday);

        // Calcular las horas nocturnas
        $nightHours = $this->calculateNightHours($start, $end);

        // Calcular las horas normales
        $normalHours = $this->calculateNormalHours($hoursWorkedCalculated, $overtimeHours, $holidayHours, $nightHours);

        // Guardar el registro de horas trabajadas en la base de datos
        $hourWorked =  HourWorked::create([
            'hour_session_id' => $hourSessionId,
            'total_normal_hours' => $isHoliday ? 0 : $normalHours,
            'total_overtime_hours' => $isHoliday ? 0 : $overtimeHours,
            'total_night_hours' => $isHoliday ? 0 : $nightHours,
            'total_holiday_hours' => $holidayHours,
        ]);

        $this->salaryService->updateSalary($hourWorked->hourSession->employee_id);
    }

    /**
     * Calcular las horas extras.
     */
    private function calculateOvertimeHours(float $hoursWorked, float $plannedHours): float
    {
        if ($hoursWorked > $plannedHours) {
            return $hoursWorked - $plannedHours;
        }
        return 0;
    }

    /**
     * Calcular las horas festivas.
     */
    private function calculateHolidayHours(float $hoursWorked, float $overtimeHours, bool $isHoliday): float
    {
        if ($isHoliday) {
            return $hoursWorked;
        }
        return 0;
    }

    /**
     * Calcular las horas nocturnas.
     * Si parte del turno cae entre las 22:00 y las 06:00, se consideran horas nocturnas.
     */
    private function calculateNightHours(Carbon $start, Carbon $end): float
    {
        // Definir el rango de horas nocturnas
        $nightStart = Carbon::createFromTime(22, 0);
        $nightEnd = Carbon::createFromTime(6, 0)->addDay();

        // Calcular las horas nocturnas
        $nightHours = 0;

        // Si el turno empieza antes de las 6 AM o después de las 10 PM, ajustamos
        if ($start->between($nightStart, $nightEnd) || $end->between($nightStart, $nightEnd)) {
            // El tiempo que empieza antes de las 6 AM cuenta como nocturno
            $nightHours += $start->max($nightStart)->diffInHours($end->min($nightEnd));
        }

        return $nightHours;
    }

    /**
     * Calcular las horas normales.
     * Las horas normales son las horas trabajadas menos las extras, nocturnas y festivas.
     */
    private function calculateNormalHours(float $hoursWorked, float $overtimeHours, float $holidayHours, float $nightHours): float
    {
        return $hoursWorked - ($overtimeHours + $holidayHours + $nightHours);
    }
}
