<?php

namespace App\Listeners;

use App\Events\HourSessionRegistered;
use App\Jobs\ProcessHourWorked;
use App\Jobs\ProcessSalary;
use App\Services\HourWorked\HourWorkedEntryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Process;

class QueueHourProcessing 
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
    public function handle(HourSessionRegistered $event): void
    {
     

     ProcessSalary::dispatch($event->getEmployeeId(), $event->getDate());  
}

}