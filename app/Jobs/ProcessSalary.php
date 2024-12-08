<?php

namespace App\Jobs;

use App\Models\Salary;
use App\Services\Salary\SalaryService;
use App\Services\Salary\SalaryServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSalary implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $employeeID, protected string $date)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SalaryServiceInterface $salaryService): void
    {
        $salaryService->execute($this->employeeID, $this->date);
    }
}
