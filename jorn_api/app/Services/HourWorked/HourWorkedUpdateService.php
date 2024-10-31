<?php 

namespace App\Services\HourWorked;

use App\Models\HourWorked;
use App\Traits\ValidateTimeEntry;
use Carbon\Carbon;

class HourWorkedUpdateService
{
    use HourCalculateTrait;
    use ValidateTimeEntry;
    public function execute( string $hourSessionId, $startTime, $endTime, $plannedHours, $isHoliday, $isOvertime)
    {
        $this->validateTimeEntry($startTime, $endTime);

        // Convertir los tiempos a objetos Carbon

        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);


        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        // Calcular las horas trabajadasF
        $hoursWorkedCalculated = $start->floatDiffInHours($end);

        // Calcular horas extras
        $overtimeHours = $this->calculateOvertimeHours($hoursWorkedCalculated, $plannedHours);

        // Verificar si es festivo y calcular las horas festivas
        $holidayHours = $this->calculateHolidayHours($hoursWorkedCalculated, $overtimeHours, $isHoliday);

        // Calcular las horas nocturnas
        $nightHours = $this->calculateNightHours($start, $end);

        // Calcular las horas normales
        $normalHours = $this->calculateNormalHours($hoursWorkedCalculated, $overtimeHours, $holidayHours, $nightHours);
       HourWorked::find($hourSessionId)->update([
         'total_normal_hours' => $isHoliday ? 0 : $normalHours,
            'total_overtime_hours' => $isHoliday ? 0 : $overtimeHours,
            'total_night_hours' => $isHoliday ? 0 : $nightHours,
            'total_holiday_hours' => $holidayHours,
       ]);
    }
}