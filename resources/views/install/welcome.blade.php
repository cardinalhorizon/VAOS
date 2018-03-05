<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>VAOS Online Installer</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{URL::asset('crewops/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Styles -->
    <link href="/css/simple-line-icons.css" rel="stylesheet">
    <style>
        html,
        body {
            background-color: #fff;
            color: white;
            font-family: Arial, sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
            overflow-x: hidden;
            overflow: hidden;
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
            margin-bottom: 2%;

        }
        body {
            /* Credit to Matthew S. for the background picture */
            background: url(https://i.imgur.com/qOXmrci.png) no-repeat fixed;
            background-size: cover;
            width: 100%;
            height: 100%;
            padding: 0;
            position: relative;
            font-family: "Roboto", sans-serif;
            -webkit-font-smoothing: antialiased; /* <-- macOS Only <-- */
            z-index: 1;
        }
    </style>
</head>

<body>
<div class="flex-center position-ref full-height">

    <div class="content">
        <div class="title m-b-md">
            <div>WELCOME TO YOUR NEW VIRTUAL AIRLINE EXPERIENCE</div>
        </div>

        <div style="color: white; margin: 0px; padding: 0px;">
            <a href="{{ url('/setup?mode=settings') }}" class="btn btn-primary">Get Started</a>
        </div>
    </div>
</div>
<script src="{{ URL::asset('crewops/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ URL::asset('crewops/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="https://use.fontawesome.com/27fef86760.js"></script>
</body>

</html>