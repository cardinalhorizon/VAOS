<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>VAOS Public Beta</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('crewops/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Styles -->
    <link href="/css/simple-line-icons.css" rel="stylesheet">
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: Arial, sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }
        .full-height {
            height: 100vh;
        }
        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }
        .position-ref {
            position: relative;
        }
        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }
        .content {
            text-align: center;
        }
        .title {
            font-size: 84px;
        }
        .links > a {
            color: #636b6f;
            padding: 0 35px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
        .m-b-md {
            margin-bottom: 40px;
        }
        body {
            background: url(http://i.imgur.com/Zc7kPV3.jpg) no-repeat fixed;
            background-size: cover;
            width: 100%;
            height: 100%;
            padding: 0position: relative;
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased; /* <-- macOS Only <-- */
            z-index: 1;
        }
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
        <div class="top-right links" color: white;>
            <b style="color: white;">Flight Crew Operations Center <i style="margin-top: 4.3px;" class="icon-arrow-right"></i></b>
            <a style="color: white;" href="{{ url('/login') }}">Login </a>
        </div>
        @endif

        <div class="content">
            <div class="title m-b-md">
                <img src="http://i.imgur.com/Ksnw6Ue.png" style="max-width: 35%; ">
            </div>

            <div class="links" style="color: white; margin: 0px; padding: 0px;">
                <b>{{ config('app.version') }}</b>
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('crewops/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('crewops/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
</body>

</html>