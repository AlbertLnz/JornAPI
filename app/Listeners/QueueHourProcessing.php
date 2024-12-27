<?php

namespace App\Listeners;

use App\Events\HourSessionRegistered;
use App\Jobs\ProcessSalary;


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