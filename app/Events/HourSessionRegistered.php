<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class HourSessionRegistered
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    /**
     * Summary of __construct
     *
     * @param  \App\Models\HourSession  $hourSession
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
