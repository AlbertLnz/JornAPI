<?php

namespace App\Jobs;

use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedUpdateService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateHourWorked implements ShouldQueue
{
    use Queueable;

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
    public function handle(HourWorkedUpdateService $hourWorkedUpdateService): void
    {
        $hourWorkedUpdateService->execute(
            $this->hourSession->id,
            $this->hourSession->start_time,
            $this->hourSession->end_time,
            $this->hourSession->planned_hours,
            $this->hourSession->work_type
        );
    }
}
