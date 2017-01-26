<head>
    <title>VAOS Online Installer</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap Core CSS -->
    <link href="{{URL::asset('crewops/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
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
    <nav class="navbar navbar-default navbar-fixed-bottom">
        <p class="pull-left navbar-text">Virtual Airline Operations System | {{ config('app.version') }}</p>
        <p class="pull-right navbar-text">Copyright &copy; 2017 Cardinal Horizon</p>
    </nav>
</body>