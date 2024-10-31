<?php

namespace App\Listeners;

use App\Events\HourSessionUpdatedEvent;
use App\Jobs\ProcessSalary;
use App\Jobs\UpdateHourWorked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Process;

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
