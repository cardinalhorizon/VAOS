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
    <title>
        {{ config('app.name') }} Login
    </title>
    <!-- Icons -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simple-line-icons.css') }}" rel="stylesheet">
    <!-- Main styles for this application -->
    <link href="{{ asset('css/materialcrew.css') }}" rel="stylesheet">
    <style>
        body {
            background: url({{asset('/img/login.jpg')}}) no-repeat center;
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
<div class="login-box white-text z-depth-4" style="min-width: 25rem;">
    <div class="login-title">{{ config('app.name') }} Login</div>
    <form action="{{ url('/login') }}" class="form-horizontal" method="POST" role="form">
        {{ csrf_field() }}
        <div class="row">
            <div class="input-field col s12">
                <input name="username" id="username" type="text">
                <label for="username">Username or Email</label>
            </div>
            <div class="input-field col s12">
                <input name="password" id="password" type="password">
                <label for="password">Password</label>
                <span class="helper-text" data-error="wrong" data-success="right"><a href="{{ route('password.request') }}">Forgot Password?</a></span>
            </div>
        </div>
        <div style="margin: 1rem;">
            Not a member? <a href="{{route('register')}}">Register</a>
            <button class="waves-effect waves-light btn" style="float: right;margin-bottom: .5rem;" type="submit">
                Login
            </button>
        </div>
    </form>
</div>
<!-- Make me happy. Don't remove this when/if you modify this page. Thanks!!! -->
<div class="vaos-box">
    <div>Powered By</div>
    <img src="{{asset('img/MainLogo.svg')}}" style="height: 50px; margin: auto;">
    <div>Version {{config('app.version')}}</div>
</div>
{{--
<div class="container-fluid" style="height: 100% !important;">
    <div class="card p-a-2" style="margin: 0px;">
        <div class="card" style="padding-bottom: 180%; padding-top: 15%; margin: 0px; height: 100% !important;">
            <h1>
                {{ config('app.name') }} Login
            </h1>
            <p class="text-muted">
                Sign In to your account
            </p>
            <form action="{{ url('/login') }}" class="form-horizontal" method="POST" role="form">
                {{ csrf_field() }}
                <div class="input-group m-b-1">
                                        <span class="input-group-addon">
                                            <i class="icon-user">
                                            </i>
                                        </span>
                    <input class="form-control" id="username" name="username" placeholder="Username or Email" type="text">
                    </input>
                </div>
                <div class="input-group m-b-2">
                                        <span class="input-group-addon">
                                            <i class="icon-lock">
                                            </i>
                                        </span>
                    <input class="form-control" id="password" name="password" placeholder="Password" type="password">
                    <div>
                    </div>
                    </input>
                </div>
                @if ($errors->has('password'))
                    <div class="alert alert-danger" role="alert">
                        <strong>
                            Whoops!
                        </strong>
                        {{ $errors->first('password') }}
                    </div>
                @endif @if ($errors->has('username'))
                    <div class="alert alert-danger" role="alert">
                        <strong>
                            Oh snap!
                        </strong>
                        {{ $errors->first('username') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-xs-6">
                        <button class="btn btn-primary p-x-2" type="submit">
                            Login
                        </button>
                    </div>
                    <div class="col-xs-6 text-xs-right">
                        <a href="{{ url('/password/reset') }}">
                            <button class="btn btn-link p-x-0" type="button">
                                Forgot password?
                            </button>
                        </a>
                    </div>
                </div>
                <div class="register">
                    <div class="row">
                        <p style="display: inline;">
                            Not a member yet?
                        </p>
                        <a href="{{ url('/register') }}">
                            <button class="btn btn-primary" style="display: inline;" type="button">
                                Register
                            </button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
--}}
<!-- Bootstrap and necessary plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
