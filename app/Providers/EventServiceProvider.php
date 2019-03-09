<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\FlightCompleted' => [
            'App\Listeners\DiscordNotification',
            'App\Listeners\EmailNotification',
        ],
        'App\Events\BidRequested' => [
            'App\Listeners\DiscordNotification',
            'App\Listeners\EmailNotification',
        ],
        'App\Events\BidModified' => [
            'App\Listeners\DiscordNotification',
            'App\Listeners\EmailNotification',
        ],
        'App\Events\BidRemoved' => [
            'App\Listeners\DiscordNotification',
            'App\Listeners\EmailNotification',
        ],
        'App\Events\AirlineEventPublished' => [
            'App\Listeners\DiscordNotification',
            'App\Listeners\EmailNotification',
        ],
        'App\Events\AirineEventDispatched' => [
            'App\Listeners\DiscordNotification',
            'App\Listeners\EmailNotification',
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

        //
    }
    protected $subscribe = [
        'App\Listeners\AdminEmailSubscriber',
    ];
}
