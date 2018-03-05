<head>
    <title>VAOS Online Installer</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap Core CSS -->
    <link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-9">
                <img src="img/VAOSLogo.png" style="width: 100%; margin-top: 20px;"/>
            </div>
            <div class="col-lg-8 col-sm-12">
                <h1>Virtual Airline Operations System Web Setup</h1>
                @yield('pageinfo')
            </div>
        </div>
        @yield('content')
    </div>
    <nav class="navbar navbar-expand-lg fixed-bottom navbar-light bg-light">
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#">Virtual Airline Operations System | Version {{ config('app.version') }}</a>
            <div class="my-2 my-lg-0" style="position: absolute; right: 1rem;">
                <div>Copyright 2018 Cardinal Horizon Inc.</div>
            </div>
        </div>
    </nav>
</body>