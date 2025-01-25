<?php

namespace App\Jobs;

use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedEntryService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;

class HourWorkedEntryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private HourSession $hourSession)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(HourWorkedEntryService $hourWorkedEntryService): void
    {
        $hourWorkedEntryService->execute(
            $this->hourSession->id,
            $this->hourSession->start_time,
            $this->hourSession->end_time,
            $this->hourSession->planned_hours,
            $this->hourSession->work_type
        );
    }
}
