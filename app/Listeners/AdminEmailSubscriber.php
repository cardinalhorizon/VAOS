<?php

namespace App\Listeners;

use App\Mail\UserApplicationReceived;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class AdminEmailSubscriber
{
    /**
     * Handle user login events.
     */
    public function onRegisterUser($event) {
        // Get all admins.
        $admins = User::where('admin', true)->get();
        foreach ($admins as $admin) {
            Mail::to($admin)->send(new UserApplicationReceived($event->user, $admin));
        }
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event) {}

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Registered',
            'App\Listeners\AdminEmailSubscriber@onRegisterUser'
        );
    }
}
