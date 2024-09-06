<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimeEntryController extends Controller
{
    /**
     * Handle the request to fetch time entry data.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // Obtener el parámetro 'date' de la solicitud
        $date = $request->query('date');
        
        // Validar que la fecha esté en formato correcto
        if (!$this->isValidDate($date)) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        // Aquí podrías hacer una consulta a la base de datos o procesar la fecha
        // Para este ejemplo, devolveremos una respuesta hardcodeada

        $timeEntryData = [
            'date' => $date,
            'expectedHours' => '8',
            'entryTime' => '09:00',
            'exitTime' => '17:00',
            'isHoliday' => rand(0, 1) === 1
        ];

        return response()->json($timeEntryData);
    }

    /**
     * Validate date format (YYYY-MM-DD).
     *
     * @param string $date
     * @return bool
     */
    private function isValidDate(string $date): bool
    {
        $format = 'Y-m-d';
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
