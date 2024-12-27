<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HourSessionUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    /**
     * Summary of __construct
     */
    public function __construct(private string $employeeId, private string $date)
    {
        //
    }

    /**
     * Summary of getEmployeeId
     *
     * @return string
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * Summary of getDate
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
}
