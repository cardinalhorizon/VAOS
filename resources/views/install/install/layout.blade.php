<head>
    <title>VAOS Online Installer</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap Core CSS -->
    <link href="{{URL::asset('crewops/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-lg-4 col-sm-12">
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
        <p class="pull-left navbar-text">Virtual Airline Operations System Version 1.0.0 Beta 2</p>
        <p class="pull-right navbar-text">Copyright 2017 Cardinal Horizon</p>
    </nav>
</body>