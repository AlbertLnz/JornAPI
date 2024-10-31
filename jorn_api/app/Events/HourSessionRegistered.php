<?php

namespace App\Events;

use App\Models\HourSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HourSessionRegistered
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(private HourSession $hourSession, private string $employeeId, private string $date) 
    {
        //
    }

    public function getEmployeeId()    
    {

        return $this->employeeId;
    } 

    public function getHourSession()
    {
        return $this->hourSession;
    }

    public function getDate()
    {
        return $this->date;
    }

    

    
}
