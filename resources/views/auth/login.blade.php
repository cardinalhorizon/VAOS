<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ config('app.name', 'VAOS') }} | Login to your airline">
    <!-- <link rel="shortcut icon" href="http://vaos.tk/assets/ico/favicon.png"> -->
    <title>Virtual Airline Operations System Login</title>
    <!-- Icons -->
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/simple-line-icons.css" rel="stylesheet">
    <style>
        body {
            background: url(http://i.imgur.com/Zc7kPV3.jpg) no-repeat fixed;
            background-size: cover;
            width: 100%;
            height: 100%;
            padding: 0position: relative;
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased;
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
    <!-- Main styles for this application -->
    <link href="/css/style.css" rel="stylesheet"> </head>

<body style="overflow-x: hidden; overflow-y: hidden;" class="noscroll">
    <div class="container-fluid" style="height: 100% !important;">
        <div class="row">
            <div class="col-md-8 element"> <img src="http://i.imgur.com/Ksnw6Ue.png" style="width: 500px; "> </div>
            <div class="col-md-4" style="margin: 0px; padding: 0px;">
                <div class="card-group" style="margin: 0px; padding: 0px;">
                    <div class="card p-a-2" style="margin: 0px;">
                        <div class="card-block" style="padding-bottom: 150%; padding-top: 15%; margin: 0px; height: 100% !important;">
                            <h1>VAOS Login</h1>
                            <p class="text-muted">Sign In to your account</p>
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}"> {{ csrf_field() }}
                                <div class="input-group m-b-1"> <span class="input-group-addon">
                                        <i class="icon-user"></i>
                                        </span>
                                    <input type="text" id="email" class="form-control" name="email" placeholder="Email"> </div>
                                <div class="input-group m-b-2"> <span class="input-group-addon">
                                        <i class="icon-lock"></i>
                                        </span>
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Password">
                                    <div></div>
                                </div> @if ($errors->has('password'))
                                <div class="alert alert-danger" role="alert"> <strong>Whoops!</strong> {{ $errors->first('password') }} </div> @endif @if ($errors->has('email'))
                                <div class="alert alert-danger" role="alert"> <strong>Oh snap!</strong> {{ $errors->first('email') }} </div> @endif
                                <div class="row">
                                    <div class="col-xs-6">
                                        <button type="submit" class="btn btn-primary p-x-2">Login</button>
                                    </div>
                                    <div class="col-xs-6 text-xs-right">
                                    <a href="{{ url('/password/reset') }}">
                                        <button type="button" class="btn btn-link p-x-0">Forgot password?</button>
                                    </a>
                                    </div>
                                </div>
                                <div class="register">
                                    <div class="row">
                                        <p style="display: inline;">Not yet a member? </p>
                                        <a href="{{ url('/register') }}">
                                            <button style="display: inline;" type="button" class="btn btn-primary">Register</button>
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
    <script src="http://vaos.tk/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="http://vaos.tk/bower_components/tether/dist/js/tether.min.js"></script>
    <script src="http://vaos.tk/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        $(' body ').css('top ', -(document.documentElement.scrollTop) + 'px ').addClass('noscroll ');
    </script>
</body>