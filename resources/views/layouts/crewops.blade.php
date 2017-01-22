<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Crew Operations</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{URL::asset('crewops/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{URL::asset('crewops/vendor/metisMenu/metisMenu.min.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{URL::asset('crewops/dist/css/sb-admin-2.css')}}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{{URL::asset('crewops/vendor/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{URL::asset('crewops/dist/css/custom.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{URL::asset('css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    @yield('head')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{ URL::asset('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') }}"></script>
    <script src="{{ URL::asset('https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js') }}"></script>
    <![endif]-->

</head>

<body>
<div class="wrapper">
<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.html">{{ config('app.name', 'VAOS') }} Operations Center</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">

        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> {{ Auth::user()->username }} <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="{{ url('flightops/profile/' . Auth::id()) }}"><i class="fa fa-user fa-fw"></i> My Profile</a>
                </li>
                <li class="divider"></li>
                <li><a class="dropdown-item" href="#" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-lock"></i> Logout</a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                </li>
                <li>
                    <a href="{{ url('/flightops') }}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ url('/flightops/roster') }}"><i class="fa fa-group fa-fw"></i> Roster</a>
                </li>
                <li>
                    <a href="{{ action('CrewOps\CrewOpsController@profileShow', ['id' => Auth::user()->id]) }}"><i class="fa fa-user fa-fw"></i> My Profile</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-plane fa-fw"></i> Schedule<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="{{ url('/flightops/schedule') }}">View Schedule</a>
                        </li>
                        <li>
                            <a href="{{ url('/flightops/bids') }}">View Bids</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                 @if(env('VAOS_FF_ENABLED'))
                 <li>
                    <a href="{{ url('/flightops/freeflight') }}"><i class="fa fa-book fa-fw"></i> Free Flight</a>
                </li>
                @endif
                <li>
                    <a href="{{ url('/flightops/logbook') }}"><i class="fa fa-book fa-fw"></i> Logbook</a>
                </li>
                @if (Auth::user()->admin)
                    <li>
                        <a href="{{ url('/admin') }}"><i class="fa fa-database fa-fw"></i> Admin Panel</a>
                    </li>
                @endif
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>
    <div id="page-wrapper">
        @yield('content')
    </div>
</div>
<!-- jQuery -->
<script src="{{ URL::asset('crewops/vendor/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ URL::asset('crewops/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="{{ URL::asset('crewops/vendor/metisMenu/metisMenu.min.js') }}"></script>

<!-- Morris Charts JavaScript -->
<script src="{{ URL::asset('crewops/vendor/raphael/raphael.min.js') }}"></script>
<script src="{{ URL::asset('crewops/vendor/morrisjs/morris.min.js') }}"></script>
<script src="{{ URL::asset('crewops/data/morris-data.js') }}"></script>
@yield('js')
<!-- Custom Theme JavaScript -->
<script src="{{ URL::asset('crewops/dist/js/sb-admin-2.js') }}"></script>
</body>