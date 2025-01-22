<?php

namespace App\Listeners;

use App\Events\HourSessionRegistered;
use App\Events\RegisteredHourSessionEvent;
use App\Jobs\HourWorkedEntryJob;
use App\Jobs\ProcessSalary;
use Illuminate\Support\Facades\Log;

class QueueHourProcessing
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        // Constructor vacío, pero puedes inyectar dependencias si es necesario
    }

    /**
     * Handle the event.
     */
    public function handle(RegisteredHourSessionEvent $event): void
    {
        Log::info('QueueHourProcessing listener iniciado.', [
            'event' => 'HourSessionRegistered',
            'employeeId' => $event->getEmployeeId(),
            'date' => $event->getDate(),
            'hourSessionId' => $event->getHourSession()->id,
        ]);

        try {
            Log::info('Disparando HourWorkedEntryJob con encadenamiento de ProcessSalary.', [
                'hourSessionId' => $event->getHourSession()->id,
                'employeeId' => $event->getEmployeeId(),
                'date' => $event->getDate(),
            ]);

            HourWorkedEntryJob::withChain([
                new ProcessSalary($event->getEmployeeId(), $event->getDate()),
            ])
                ->onConnection('redis')
                ->onQueue('inserts')
                ->dispatch($event->getHourSession());

            // Log de éxito
            Log::info('Jobs disparados exitosamente.', [
                'hourSessionId' => $event->getHourSession()->id,
            ]);
        } catch (\Exception $e) {
            // Log en caso de error
            Log::error('Error al procesar el encadenamiento de jobs.', [
                'error' => $e->getMessage(),
                'employeeId' => $event->getEmployeeId(),
                'hourSessionId' => $event->getHourSession()->id ?? null,
            ]);

            // Relanzar la excepción para que Laravel maneje el error
            throw $e;
        }
    }
}
