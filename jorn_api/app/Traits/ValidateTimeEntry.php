<?php

namespace App\Traits;

use App\Exceptions\TimeEntryException;

trait ValidateTimeEntry
{
    public function validateTimeEntry(string $startTime, string $endTime): void
    {
        if($startTime > $endTime){
            throw new TimeEntryException();
        }
    }
}
