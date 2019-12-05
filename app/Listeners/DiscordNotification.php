<?php

namespace App\Listeners;

use App\Events\AirineEventDispatched;

class DiscordNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        // Get the Discord Webhook from file
    }

    /**
     * Handle the event.
     *
     * @param  AirineEventDispatched  $event
     *
     * @return void
     */
    public function handle(AirineEventDispatched $event)
    {
        //
    }
}
