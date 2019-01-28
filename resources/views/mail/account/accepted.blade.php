@component('mail::message')

Hi {{ $user->first_name }},

We are please to announce your account has been accepted and we would like to welcome you to {{ config('app.name') }}.

To get started, all you need to do is click the button below to take you to the login screen and subsequently the dashboard.

@component('mail::button', ['url' => url('/login')])
Login Now
@endcomponent

Thanks,<br>
{{ config('app.name') }} Staff
@endcomponent
