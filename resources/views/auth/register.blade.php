<!DOCTYPE html>
<html lang="en">
<head>
    @if(View::exists('allpagescripts'))
        @include('allpagescripts')
    @endif
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
    <div style="background: #333; padding-top: 2rem;margin-top: 20vh;">
        <div style="font-size: 3rem;margin-bottom: 2rem; text-align: center;">Create New Account</div>
        <div></div>
        <pub-register-pilot></pub-register-pilot>
    </div>
</div>
{{--
<div class="container-fluid" style="height: 300% !important;">
    <div class="row">
        <div class="col-md-8 element">
            <img src="https://i.imgur.com/0BEIm3k.png" style="width: 500px; ">
            </img>
        </div>
        <div class="col-md-4" style="margin: 0px; padding: 0px;">
            <div class="card-group" style="margin: 0px; padding: 0px;">
                <div class="card p-a-2" style="margin: 0px;">
                    <div class="card-block" style="padding-bottom: 300% !important; padding-top: 15%; margin: 0px; height: 300% !important;">
                        <h1>
                            {{ config('app.name') }} Registration
                        </h1>
                        <p class="text-muted">
                            Create an account
                        </p>
                        <form action="{{ url('/register') }}" method="POST" role="form">
                            {{ csrf_field() }}
                            <div class="input-group m-b-1">
                                        <span class="input-group-addon">
                                            <i class="icon-user">
                                            </i>
                                        </span>
                                <input autofocus="" class="form-control" name="first_name" placeholder="First Name" required="" type="text">
                                </input>
                            </div>
                            <div class="input-group m-b-1">
                                        <span class="input-group-addon">
                                            <i class="icon-user">
                                            </i>
                                        </span>
                                <input class="form-control" name="last_name" placeholder="Last Name" required="" type="text">
                                </input>
                            </div>
                            <div class="input-group m-b-1">
                                        <span class="input-group-addon">
                                            <i class="icon-user">
                                            </i>
                                        </span>
                                <input class="form-control" name="username" placeholder="Username" required="" type="text">
                                </input>
                            </div>
                            <div class="input-group m-b-1">
                                        <span class="input-group-addon">
                                            @
                                        </span>
                                <input class="form-control" name="email" placeholder="Email" required="" type="text">
                                </input>
                            </div>
                            <div class="input-group m-b-1">
                                        <span class="input-group-addon">
                                            <i class="icon-lock">
                                            </i>
                                        </span>
                                <input class="form-control" name="password" placeholder="Password" required="" type="password">
                                </input>
                            </div>
                            <div class="input-group m-b-2">
                                        <span class="input-group-addon">
                                            <i class="icon-lock">
                                            </i>
                                        </span>
                                <input class="form-control" name="password_confirmation" placeholder="Repeat password" required="" type="password">
                                </input>
                            </div>
                            <button class="btn btn-block btn-primary" type="submit">
                                Create Account
                            </button>
                            <div class="register">
                                <div class="row">
                                    <a href="/">
                                        <button class="btn btn-primary" style="display: inline;" type="button">
                                            <i class="icon-arrow-left" style="padding-top: 1.8px;">
                                            </i>
                                            Go Back
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
--}}
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