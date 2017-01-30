<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ config('app.name', 'VAOS') }} | Login to your airline" name="description">
    <!-- <link rel="shortcut icon" href="{{ asset('assets/ico/favicon.png') }}"> -->
    <title>
        VAOS Registration
    </title>
    <!-- Icons -->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/simple-line-icons.css') }}" rel="stylesheet">
        <style>
        
        body {
        background: url(http://i.imgur.com/lH3nDFa.png) no-repeat fixed;
        background-size: cover;
        width: 100%;
        height: 300%;
        padding: 0;
        position: relative;
        font-family: "Roboto", sans-serif;
        -webkit-font-smoothing: antialiased; /* <-- macOS Only <-- */
        z-index: 1;
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
            
            .card-block {
                padding-bottom: 80% !important;
                padding-top: 0% !important;
            }
            
            html, body{
            max-width:100%;
            max-height:300%;
            overflow:hidden;
            }

            .element {
            display: none;
            visibility: hidden;
            }
        }
        
        .element img {
        display: block;
        margin: auto;
        }

    </style>
    <!-- Main Styles For this App -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        </link>
    </head>
    <body class="noscroll" style="overflow-x: hidden !important; overflow-y: hidden !important; position: relative;">
        <div class="container-fluid" style="height: 300% !important;">
            <div class="row">
                <div class="col-md-8 element">
                    <img src="http://i.imgur.com/Ksnw6Ue.png" style="width: 500px; ">
                    </img>
                </div>
                <div class="col-md-4" style="margin: 0px; padding: 0px;">
                    <div class="card-group" style="margin: 0px; padding: 0px;">
                        <div class="card p-a-2" style="margin: 0px;">
                            <div class="card-block" style="padding-bottom: 300% !important; padding-top: 15%; margin: 0px; height: 300% !important;">
                                <h1>
                                    Register
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