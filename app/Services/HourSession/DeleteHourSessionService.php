<?php

declare(strict_types=1);

namespace App\Services\HourSession;

use App\Events\UpdatedHourSessionEvent;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;
use Illuminate\Support\Facades\DB;

class DeleteHourSessionService
{
    /**
     * Summary of execute
     *
     * @throws \App\Exceptions\HourSessionNotFoundException
     */
    public function execute(string $employeeId, string $date): void
    {
        $hourSession = HourSession::where('employee_id', $employeeId)->where('date', $date)->first();
        if (! $hourSession) {
            throw new HourSessionNotFoundException;
        }
        DB::transaction(function () use ($hourSession) {
            $hourSession->delete();

            DB::afterCommit(function () use ( $hourSession) {
                event(new UpdatedHourSessionEvent( $hourSession));
            });
        });

    }
}
