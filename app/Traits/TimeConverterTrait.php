<?php 
declare(strict_types=1);
namespace App\Traits;

trait TimeConverterTrait
{
    /**
     * Convierte un valor decimal de horas a horas y minutos.
     *
     * @param float $decimalHours La cantidad de horas en formato decimal (por ejemplo, 1.70).
     * @return array Un array con las horas y minutos ['hours' => int, 'minutes' => int].
     */
    public function convertDecimalToHoursAndMinutes(float $decimalHours): array
    {
        $hours = (int) $decimalHours; // Parte entera: las horas
        $minutes = (int) round(($decimalHours - $hours) * 60); // Parte fraccionaria convertida a minutos
        
        return [
            'hours' => $hours,
            'minutes' => $minutes,
        ];
    }

    /**
     * Convierte horas y minutos en formato decimal.
     *
     * @param int $hours La cantidad de horas.
     * @param int $minutes La cantidad de minutos.
     * @return float Las horas en formato decimal.
     */
    public function convertHoursAndMinutesToDecimal(int $hours, int $minutes): float
    {
        return $hours + ($minutes / 60);
    }
}
