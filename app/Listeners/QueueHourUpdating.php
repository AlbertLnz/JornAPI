<?php

namespace App\Listeners;

use App\Events\HourSessionUpdatedEvent;
use App\Jobs\ProcessSalary;
use App\Jobs\UpdateHourWorked;

class QueueHourUpdating
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(HourSessionUpdatedEvent $event): void
    {
        $date = $event->getDate();
        $employeeId = $event->getEmployeeId();
        //   $hourSession = $event->getHourSession();
        /*   UpdateHourWorked::withChain(
              [new ProcessSalary($employeeId, $date)])
              ->dispatch($hourSession); */

        ProcessSalary::dispatch($employeeId, $date);
    }
}
