<?php

namespace App\Events;

use App\Models\HourSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatedHourSessionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    /**
     * Summary of __construct
     */
    public function __construct( private HourSession $hourSession)
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
        return $this->hourSession->employee_id;
    }

    /**
     * Summary of getDate
     *
     * @return string
     */
    public function getDate()
    {
        return $this->hourSession->date;
    }

    /**
     * Summary of getHourSession
     *
     * @return \App\Models\HourSession
     */
    public function getHourSession(): HourSession
    {
        return $this->hourSession;
    }
}
