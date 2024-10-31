<?php 
namespace App\Services\HourWorked;

use Carbon\Carbon;

trait HourCalculateTrait{
       /**
     * Calcular las horas extras.
     */
    private function calculateOvertimeHours(float $hoursWorked, float $plannedHours): float
    {

        if ($hoursWorked <= $plannedHours) {
            return 0;
        }

        $overtimeHours = $hoursWorked - $plannedHours;


        return $overtimeHours;

       
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