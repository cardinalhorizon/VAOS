<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ config('app.name', 'VAOS') }} | Login to your airline">
        <!-- <link rel="shortcut icon" href="{{ asset('assets/ico/favicon.png') }}"> -->
        <title>Virtual Airline Operations System Registration</title>
        <!-- Icons -->
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/simple-line-icons.css') }}" rel="stylesheet">
        <style>
        body {
        background: url(http://i.imgur.com/Zc7kPV3.jpg) no-repeat fixed;
        background-size: cover;
        width: 100%;
        height: 100%;
        padding: 0;
        position: relative;
        font-family: "Roboto", sans-serif;
        -webkit-font-smoothing: antialiased; /* <-- macOS Only <-- */
        z-index: 1;
        }
        
        .noscroll {
        position: fixed;
        overflow-y: scroll
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
        
        .element img {
        display: block;
        margin: auto;
        }
        </style>
        <!-- Main Styles For this App -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet"> </head>
        <body style="overflow-x: hidden; overflow-y: hidden;" class="noscroll">
            <div class="container-fluid" style="height: 100% !important;">
                <div class="row">
                    <div class="col-md-8 element"> <img src="http://i.imgur.com/Ksnw6Ue.png" style="width: 500px; "> </div>
                    <div class="col-md-4" style="margin: 0px; padding: 0px;">
                        <div class="card-group" style="margin: 0px; padding: 0px;">
                            <div class="card p-a-2" style="margin: 0px;">
                                <div class="card-block" style="padding-bottom: 150%; padding-top: 15%; margin: 0px; height: 100% !important;">
                                    <h1>Register</h1>
                                    <p class="text-muted">Create an account</p>
                                    <form role="form" method="POST" action="{{ url('/register') }}"> {{ csrf_field() }}
                                        <div class="input-group m-b-1"> <span class="input-group-addon"><i class="icon-user"></i>
                                        </span>
                                    <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') }}" autofocus required> </div>
                                    <div class="input-group m-b-1"> <span class="input-group-addon"><i class="icon-user"></i>
                                    </span>
                                <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}" required> </div>
                                <div class="input-group m-b-1"> <span class="input-group-addon"><i class="icon-user"></i>
                                </span>
                            <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}" required> </div>
                            <div class="input-group m-b-1"> <span class="input-group-addon">@</span>
                        <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required> </div>
                        <div class="input-group m-b-1"> <span class="input-group-addon"><i class="icon-lock"></i>
                        </span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required> </div>
                    <div class="input-group m-b-2"> <span class="input-group-addon"><i class="icon-lock"></i>
                    </span>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required> </div>
                
                @if(!empty(config('recaptcha.public_key') && config('recaptcha.private_key')))
                    {!! Recaptcha::render() !!}
                    <br>
                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>The following errors occurred:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="submit" class="btn btn-block btn-primary">Create Account</button>
                <div class="register">
                    <div class="row">
                        <a href="/">
                            <button style="display: inline;" type="button" class="btn btn-primary"><i style="padding-top: 1.8px;" class="icon-arrow-left"></i> Go Back</button>
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
<!-- Bootstrap and necessary plugins -->
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('bower_components/tether/dist/js/tether.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script>
$(' body ').css('top ', -(document.documentElement.scrollTop) + 'px ').addClass('noscroll ');
</script>
</body>