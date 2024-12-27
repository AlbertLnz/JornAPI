<?php

namespace App\Providers;

use App\Events\HourSessionRegistered;
use App\Events\HourSessionUpdatedEvent;
use App\Listeners\QueueHourProcessing;
use App\Listeners\QueueHourUpdating;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        HourSessionRegistered::class => [
            QueueHourProcessing::class,

        ],
        HourSessionUpdatedEvent::class => [
            QueueHourUpdating::class,
        ],

    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
