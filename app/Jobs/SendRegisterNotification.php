<?php

namespace App\Jobs;

use App\Mail\RegisterNotification;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendRegisterNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            Mail::to($this->user->email)->send(new RegisterNotification($this->user));
          }catch(Exception $e){
            Log::error($e->getMessage());
          }
    }
}
