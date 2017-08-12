<!DOCTYPE html>
<html lang="en">
        <head>
        <meta charset="utf-8">
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="width=device-width, initial-scale=1" name="viewport">
        <meta content="{{ config('app.name', 'VAOS') }} | Login to your airline" name="description">
        <!-- <link rel="shortcut icon" href="{{ asset('assets/ico/favicon.png') }}"> -->
        <title>
            VAOS Login
        </title>
        <!-- Icons -->
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/simple-line-icons.css') }}" rel="stylesheet">
        <!-- Main styles for this application -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        </link>
        </link>
        </link>
        </meta>
        </meta>
        </meta>
        </meta>
     <style>
        body {
        background: url(https://i.imgur.com/akFJhP8.jpg) no-repeat fixed;
        background-size: cover;
        width: 100%;
        height: 100%;
        padding: 0;
        position: relative;
        font-family: "Roboto", sans-serif;
        -webkit-font-smoothing: antialiased;
        z-index: 1;
        }
        
        .noscroll {
        overflow-x: hidden;
        overflow-y: hidden;
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

    <body class="noscroll">
        <div class="container-fluid" style="height: 100% !important;">
            <div class="row">
                <div class="col-md-8 element">
                    <img src="http://i.imgur.com/Ksnw6Ue.png" style="width: 500px; ">
                    </img>
                </div>
                <div class="col-md-4" style="margin: 0px; padding: 0px;">
                    <div class="card-group" style="margin: 0px; padding: 0px;">
                        <div class="card p-a-2" style="margin: 0px;">
                            <div class="card-block" style="padding-bottom: 180%; padding-top: 15%; margin: 0px; height: 100% !important;">
                                <h1>
                                    VAOS Login
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
                </div>
            </div>
        </div>
        <!-- Bootstrap and necessary plugins -->
        <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}">
        </script>
        <script src="{{ asset('bower_components/tether/dist/js/tether.min.js') }}">
        </script>
        <script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}">
        </script>
        <script>
            $(' body ').css('top ', -(document.documentElement.scrollTop) + 'px ').addClass('noscroll ');
        </script>
         <script type="text/javascript">
            //target the entire page, and listen for touch events
        $('html, body').on('touchstart touchmove', function(e){ 
        //prevent native touch activity like scrolling
        e.preventDefault(); 
            });
        </script>
    </body>
</html>
