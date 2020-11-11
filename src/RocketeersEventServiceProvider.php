<?php

namespace Rocketeers\Laravel;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Rocketeers\Laravel\Listeners\LogJobException;

class RocketeersEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        JobExceptionOccurred::class => [
            LogJobException::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
