@component('mail::message')
    Hi {{ $admin->first_name }},

    User {{ $user->first_name }} {{ $user->last_name }} has submitted his application to join.

    @component('mail::button', ['url' => url('/admin/')])
        Review His Application
    @endcomponent

@endcomponent