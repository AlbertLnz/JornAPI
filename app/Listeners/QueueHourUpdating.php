<?php

namespace App\Listeners;

use App\Events\HourSessionUpdatedEvent;
use App\Events\UpdatedHourSessionEvent;
use App\Jobs\ProcessSalary;
use App\Jobs\UpdateHourWorked;
use Illuminate\Support\Facades\Log;

class QueueHourUpdating
{
    /**
     * Handle the event.
     */
    public function handle(UpdatedHourSessionEvent $event): void
    {
        // Log inicial para registrar el evento recibido
        Log::info('QueueHourUpdating listener iniciado.', [
            'event' => 'HourSessionUpdatedEvent',
            'employeeId' => $event->getEmployeeId(),
            'date' => $event->getDate(),
            'hourSessionId' => $event->getHourSession()->id,
        ]);

        try {
            // Registrar que los jobs se están disparando
            Log::info('Disparando UpdateHourWorked con encadenamiento de ProcessSalary.', [
                'hourSessionId' => $event->getHourSession()->id,
                'employeeId' => $event->getEmployeeId(),
                'date' => $event->getDate(),
            ]);

            // Encolar el trabajo principal con su cadena
            UpdateHourWorked::withChain([
                new ProcessSalary($event->getEmployeeId(), $event->getDate()),
            ])
                ->onConnection('redis')
                ->onQueue('updates')
                ->dispatch($event->getHourSession());

            // Log de éxito
            Log::info('Jobs disparados exitosamente.', [
                'hourSessionId' => $event->getHourSession()->id,
            ]);
        } catch (\Exception $e) {
            // Log en caso de error
            Log::error('Error al procesar el encadenamiento de jobs en QueueHourUpdating.', [
                'error' => $e->getMessage(),
                'employeeId' => $event->getEmployeeId(),
                'hourSessionId' => $event->getHourSession()->id ?? null,
            ]);

            // Relanzar la excepción para que Laravel maneje el error
            throw $e;
        }
    }
}
