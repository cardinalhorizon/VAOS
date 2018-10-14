<!DOCTYPE html>
<html lang="emaintenance">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,AngularJS,Angular,Angular2,jQuery,CSS,HTML,RWD,Dashboard">

    <title>{{ config('app.name', 'VAOS') }} | VAOS Admin Dashboard</title>

    <!-- Icons -->
    <link href="{{ URL::asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/simple-line-icons.css') }}" rel="stylesheet">

    <link href="{{ URL::asset('css/custom.css') }}" rel="stylesheet">
    <!-- Main styles for this application -->
    <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
    @yield('head')
</head>

<body class="navbar-fixed sidebar-nav fixed-nav">
<header class="navbar">
    <div class="container-fluid">
        <button class="navbar-toggler mobile-toggler hidden-lg-up" type="button">☰</button>
        <a class="navbar-brand" href="{{ url('/admin') }}"></a>
        <ul class="nav navbar-nav hidden-md-down">
            <li class="nav-item">
                <a class="nav-link navbar-toggler layout-toggler" href="#">☰</a>
            </li>

            <li class="nav-item p-x-1">
                <a class="nav-link" href="{{ url('/flightops') }}">Pilot Center</a>
            </li>
            <li class="nav-item p-x-1">
                <a class="nav-link" href="{{ url('/') }}">Main Website</a>
            </li>
            <li class="nav-item p-x-1">
                <div style="margin-top: 5px">
                    @include('admin.partials.donate')
                </div>
            </li>
        </ul>
        <ul class="nav navbar-nav pull-right hidden-md-down" style="margin-right: 30px">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">

                    <span class="hidden-md-down"> {{ Auth::user()->username }} </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">

                    <div class="dropdown-header text-xs-center">
                        <strong>Settings</strong>
                    </div>

                    <a class="dropdown-item" href="{{ url('admin/users/' . Auth::id()) }}"><i class="fa fa-user"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> Settings</a>
                    <div class="divider"></div>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-lock"></i> Logout</a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </li>

        </ul>
    </div>
</header>

<div class="sidebar">

    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('admin') }}"><i class="icon-speedometer"></i> Dashboard </a>
            </li>
            <li class="divider"></li>
            <li class="nav-title">
                Administration
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-user"></i> Users</a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/admin/users') }}"><i class="fa fa-caret-right"></i> Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/admin/groups') }}"><i class="fa fa-caret-right"></i> Groups</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('admin/migrations') }}"><i class="fa fa-database"></i> Database Migrations </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Main content -->
<main class="main">

    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        @yield('breadcrumb')
        <!-- Breadcrumb Menu-->
        <li class="breadcrumb-menu">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

            </div>
        </li>
    </ol>


    <div class="container-fluid">

        <div class="animated fadeIn">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @yield('content')
        </div>

    </div>
    <!-- /.conainer-fluid -->
</main>

<footer class="footer">
        <span class="text-left">
            <a href="http://fsvaos.net"></a> © {{ date("Y") }} {{ config('app.name', 'Airline Name') }}
        </span>
    <span class="pull-right">
            Powered by <a href="http://fsvaos.net">Virtual Airline Operations System | {{ config('app.version') }}</a>
        </span>
</footer>

<!-- Bootstrap and necessary plugins -->
<script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="{{URL::asset('bower_components/tether/dist/js/tether.min.js') }}"></script>
<script src="{{URL::asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{URL::asset('bower_components/pace/pace.min.js') }}"></script>


<!-- Plugins and scripts required by all views -->
<script src="{{URL::asset('bower_components/chart.js/dist/Chart.min.js') }}"></script>


<!-- GenesisUI main scripts -->

<script src="{{URL::asset('js/app_admin.js') }}"></script>





<!-- Plugins and scripts required by this views -->

<!-- Custom scripts required by this view -->
<script src="{{URL::asset('js/views/main.js') }}"></script>

@yield('js')

</body>

</html>