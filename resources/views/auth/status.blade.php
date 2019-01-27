<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ config('app.name', 'VAOS') }} | Login to your airline" name="description">
<!-- <link rel="shortcut icon" href="{{ asset('assets/ico/favicon.png') }}"> -->
    <link href="https://fonts.googleapis.com/css?family=Cabin" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>
        {{ config('app.name') }} Register
    </title>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'baseUrl' => config('app.url')
        ]); ?>
    </script>
    <!-- Icons -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simple-line-icons.css') }}" rel="stylesheet">
    <!-- Main styles for this application -->
    <link href="{{ asset('css/materialcrew.css') }}" rel="stylesheet">
    <style>
        body {
            background: url({{asset('/img/login.jpg')}}) no-repeat center fixed;
            background-size: cover;
            width: 100vw;
            height: 100vh;
            position: relative;
            padding: 0;
            font-family: 'Cabin', sans-serif;
            -webkit-font-smoothing: antialiased;
            z-index: 1;
        }
        .login-box {
            margin: auto;
            display: block;
            position: absolute;
            top: 50%;
            background: #333;
            border-radius: 1rem;
            max-width: 30vw;
            min-width: 25rem;
            left: 50%;
            transform: translateY(-50%) translateX(-50%);
        }
        .noscroll {
            overflow-x: hidden;
            overflow-y: hidden;
        }
        .vaos-box {
            position: absolute;
            bottom: 0;
            padding: 10px 15px;
            text-align: center;
            color: white;
        }
        .register {
            position: fixed;
            bottom: 0;
            width: 100%;
            margin-bottom: 15px;
        }

        .register p {
            padding-right: 6px;
        }
        .element {
            transform: translateY(165%);
        }
        .login-title {
            padding: 1rem 0;
            font-size: 2rem;
            color:white;
            text-align: center;
        }
        @media (max-width: 770px) {

            .element {
                display: none;
                visibility: hidden;
            }
            html, body{
                max-width:100%;
                max-height:100%;
                overflow:hidden;
            }
        }
        .element img {
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>
<div class="container white-text" id="appReg">
    <div style="background: #333; padding: 2rem;margin-top: 20vh;">
        @if(session('AppInProgress') || Auth::user()->status === 0)
            <div style="font-size: 3rem;margin-bottom: 2rem; text-align: center;">Account Pending Activation</div>
            <p style="font-size: 1.5rem">
                Thank you for your interest in our organization. Your account is pending approval by the site administrators.
                Once your account is approved, you will receive an email.
            </p>
            <p style="font-size: 1.5rem">{{ config('app.name') }} Staff</p>
        @elseif(session('AccountDisabled') || Auth::user()->status === 2)
            <div style="font-size: 3rem;margin-bottom: 2rem; text-align: center;">Account Disabled</div>
            <p style="font-size: 1.5rem">
                Your account has been disabled. Please contact site administrators if this was in error.
            </p>
            <p style="font-size: 1.5rem">{{ config('app.name') }} Staff</p>
        @endif
        <div style="margin-top: 2rem;">
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                <div style="margin: 0 auto; text-align: center;">
                    <button class="btn" type="submit">Go Back</button>
                </div>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</div>
<!-- Bootstrap and necessary plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="{{asset('/js/app.js')}}"></script>
</body>
</html>
<script>
    import PubRegisterPilot from "../../assets/js/components/Registration/PubRegisterPilot";
    export default {
        components: {PubRegisterPilot}
    }
</script>